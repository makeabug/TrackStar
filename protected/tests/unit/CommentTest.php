<?php
class CommentTest extends CDbTestCase 
{
    public $fixtures = array(
        'comments' => 'Comment',
        'projects' => 'Project',
        'issue' => 'Issue',
    );
    
    public function testRecentComments()
    {
        $recentComments = Comment::findRecentComments();
        $this->assertTrue(is_array($recentComments));
        $this->assertEquals(count($recentComments), 3);
        
         //make sure the limit is working 
        $recentComments = Comment::findRecentComments(2); 
        $this->assertTrue(is_array($recentComments)); 
        $this->assertEquals(count($recentComments),2);
        
        //test retrieving comments only for a specific project 
        $recentComments = Comment::findRecentComments(5, 3); 
        $this->assertTrue(is_array($recentComments)); 
        $this->assertEquals(count($recentComments),1);
    }
}
?>