<?php
class ProjectTest extends CDbTestCase 
{
    public $fixtures = array(
        'projects' => 'Project',
        'users' => 'User',
        'projUsrAssign' => ':tbl_project_user_assignment',
        'projUserRole' => ':tbl_project_user_role',
        'authAssign'=>':AuthAssignment',
    );
    
    public function testCreate()
    {
        //Create a new project
        $newProject = new Project();
        $newProjectName = 'Test Project Creation';
         
        $newProject->setAttributes(array( 
            'name' => $newProjectName, 
            'description' => 'This is a test for new project creation', 
            /*'createTime' => '2009-09-09 00:00:00', 
            'createUser' => '1', 
            'updateTime' => '2009-09-09 00:00:00', 
            'updateUser' => '1', */
        ));
        
        Yii::app()->user->setId($this->users('user1')->id);
 
        $this->assertTrue($newProject->save()); 
        
        //READ back the newly created Project to ensure the creation worked
        $retrievedProject=Project::model()->findByPk($newProject->id);
        $this->assertTrue($retrievedProject instanceof Project);
        $this->assertEquals($newProjectName, $retrievedProject->name);
        //var_dump(Yii::app()->user->id, $retrievedProject, $retrievedProject->create_user_id);exit;
        $this->assertEquals(Yii::app()->user->id, $retrievedProject->create_user_id);
    }
    
    public function testRead() 
    { 
        $retrievedProject = $this->projects('project1'); 
        $this->assertTrue($retrievedProject instanceof Project); 
        $this->assertEquals('Test Project 1',$retrievedProject->name); 
    }
    
    public function testUpdate() 
    {
        $project = $this->projects('project2');
        $updatedProjectName = 'Updated Test Project 2';
        $project->name = $updatedProjectName;
        $this->assertTrue($project->save(false)); 
        //read back the record again to ensure the update worked
        $updatedProject=Project::model()->findByPk($project->id);
        $this->assertTrue($updatedProject instanceof Project);
        $this->assertEquals($updatedProjectName,$updatedProject->name);
    }
    
    public function testDelete()
    { 
        $project = $this->projects('project2'); 
        $savedProjectId = $project->id; 
        $this->assertTrue($project->delete()); 
        $deletedProject=Project::model()->findByPk($savedProjectId); 
        $this->assertEquals(NULL,$deletedProject); 
    }
    
    public function testGetUserOptions()
    {
        $project = $this->projects('project1');
        $options = $project->userOptions;
        $this->assertTrue(is_array($options));
        $this->assertTrue(count($options) > 0);
    }
    
    public function testUserRoleAssignment()
    {
        $project = $this->projects('project1');
        $user = $this->users('user1');
        $this->assertEquals(1, $project->associateUserToRole('owner', $user->id));
        $this->assertEquals(1, $project->removeUserFromRole('owner', $user->id));
    }
    
    public function testIsInRole()
    {
        $row1 = $this->projUserRole['row1'];
        Yii::app()->user->setId($row1['user_id']);
        $project = Project::model()->findByPk($row1['project_id']);
        $this->assertTrue($project->isUserInRole('member'));
    }
    
    public function testUserAccessBaseOnProjectRole()
    {
        $row1 = $this->projUserRole['row1'];
        Yii::app()->user->setId($row1['user_id']);
        $project = Project::model()->findByPk($row1['project_id']);
        $auth = Yii::app()->authManager;
        $bizRule = 'return isset($params["project"]) && $params["project"]->isUserInRole("member");';
        $auth->assign('member', $row1['user_id'], $bizRule);
        $params = array('project'=>$project);
        $this->assertTrue(Yii::app()->user->checkAccess('updateIssue', $params));
        $this->assertTrue(Yii::app()->user->checkAccess('readIssue', $params));
        $this->assertFalse(Yii::app()->user->checkAccess('updateProject', $params));
        
        //now ensure the user does not have any access to a project they are not associated with
        $project=Project::model()->findByPk(1); 
        $params=array('project'=>$project); 
        $this->assertFalse(Yii::app()->user->checkAccess('updateIssue', $params)); 
        $this->assertFalse(Yii::app()->user->checkAccess('readIssue', $params)); 
        $this->assertFalse(Yii::app()->user->checkAccess('updateProject', $params));
    }
    
    public function testGetUserRoleOptions() 
    {
        $options = Project::getUserRoleOptions();
        $this->assertEquals(count($options),3);
        $this->assertTrue(isset($options['reader']));
        $this->assertTrue(isset($options['member']));
        $this->assertTrue(isset($options['owner']));
    }
     
    public function testUserProjectAssignment() {
        //since our fixture data already has the two users 
        //assigned to project 1, we'll assign user 1 to project 2
        $this->projects('project2')->associateUserToProject($this- >users('user1'));
        $this->assertTrue($this->projects('project1')->isUserInProject($this->users('user1')));
    }
        
    /*public function testCURD()
    {
        //Create a new project
        $newProject = new Project();
        $newProjectName = 'Test Project 1';
        $newProject->setAttributes(array(
            'name' => $newProjectName,
            'description' => 'Test projecr number one',
            'create_time' => '2012-11-11 00:00:00',
            'create_user_id' => 1,
            'update_time' => '2012-11-11 00:00:00',
            'update_user_id' => 1
        ));
        $this->assertTrue($newProject->save(false));
        
        //Read back the newly created project
        $retrievedProject = Project::model()->findByPk($newProject->id);
        $this->assertTrue($retrievedProject instanceof Project );
        $this->assertEquals($newProjectName, $retrievedProject->name);
        
        //Update the newly created project;
        $updatedProjectName = 'Updated Test Project 1';
        $newProject->name = $updatedProjectName;
        $this->assertTrue($newProject->save(false));
        
        //Read back the record again to ensure the update worked
        $updatedProject = Project::model()->findByPk($newProject->id);
        $this->assertTrue($updatedProject instanceof Project );
        $this->assertEquals($updatedProjectName, $updatedProject->name);
        
        //Delete the project
        $newProjectId = $newProject->id;
        $this->assertTrue($newProject->delete());
        $deleteProject = Project::model()->findByPk($newProjectId);
        $this->assertEquals(null, $deleteProject);
    }*/
}
?>