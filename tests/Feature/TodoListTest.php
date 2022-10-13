<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     private $list;

     public function setUp() : void {
        parent::setUp();
        $this->list = $this->createTodoList(['name'=>'my list']);
        
     }



    public function test_fetch_all_todo_list()
    {

        // $this->withoutExceptionHandling();
        // Preparation  /Prepare 


        // TodoList::factory()->create(['name'=>'my list']);
     

        //Action    /Perform
       
        $response = $this->getJson(route('todo-list.index'));

        // $response = $this->getJson('api/todo-list');
        // dd($response->json());   


        // Assertion    /Predict

        $this->assertEquals(1,count($response->json()));
        $this->assertEquals('my list',$response->json()[0]['name']);
    }
    public function test_fetch_single_todo_list(){
        // Preparation


        // $list = TodoList::factory()->create();

        //Action

        $response = $this->getJson(route('todo-list.show',$this->list->id))->assertOk()->json();
        // dd($response);


        //Assertion
        $this->assertEquals($response['name'], $this->list->name);
    }

    public function test_store_new_todo_list(){
         // Preparation

        $list = TodoList::factory()->make();


         // Action

        $response = $this->postJson(route('todo-list.store'),['name' => $list->name])->assertCreated()->json();


         //Assertion
         $this->assertEquals($list->name,$response['name']);
         $this->assertDatabaseHas('todo_lists',['name' => $list->name]);
    }
    public function test_while_storing_todo_list_name_field_is_required(){
        $this->withExceptionHandling();
        $this->postJson(route('todo-list.store'))->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }
    public function test_delete_todo_list(){
        $this->deleteJson(route('todo-list.destroy',$this->list->id))->assertNoContent();
        $this->assertDatabaseMissing('todo_lists',['name'=> $this->list->name]);
    }
    public function test_update_todo_list(){
        $this->putJson(route('todo-list.update',$this->list->id),['name'=>'updated name'])->assertOk();

        $this->assertDatabaseHas('todo_lists',['id'=>$this->list->id, 'name'=>'updated name']);
    }

    public function test_while_update_todo_list_name_field_is_required(){
        $this->withExceptionHandling();
        $this->putJson(route('todo-list.update',$this->list->id))->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }
       
}
