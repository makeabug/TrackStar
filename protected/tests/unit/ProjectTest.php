<?php
class ProjectTest extends CDbTestCase 
{
    public $fixtures = array(
        'projects' => 'Project'
    );
    
    public function testCreate()
    {
        //Create a new project
        $newProject = new Project();
        $newProjectName = 'Test Project Creation';
         
        $newProject->setAttributes(array( 
            'name' => $newProjectName, 
            'description' => 'This is a test for new project creation', 
            'createTime' => '2009-09-09 00:00:00', 
            'createUser' => '1', 
            'updateTime' => '2009-09-09 00:00:00', 
            'updateUser' => '1', 
        ));
 
        $this->assertTrue($newProject->save(false)); 
        
        //READ back the newly created Project to ensure the creation worked
        $retrievedProject=Project::model()->findByPk($newProject->id);
        $this->assertTrue($retrievedProject instanceof Project);
        $this->assertEquals($newProjectName,$retrievedProject->name);
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