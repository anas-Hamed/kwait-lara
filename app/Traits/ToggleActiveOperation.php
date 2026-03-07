<?php
namespace App\Traits;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;
/**
 * @property-read CrudPanel $crud
 */
trait ToggleActiveOperation{
    protected  $disable = true;
    protected function setupToggleActiveDefaults()
    {
        $this->crud->allowAccess(['toggle-active']);
        $this->crud->operation('list', function () {
            $this->toggleActiveButton();
        });

        $this->crud->operation('show', function () {
            $this->toggleActiveButton();
        });
    }
    protected function setupToggleActiveRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/toggle-active', [
            'as'        => $routeName.'.toggleActive',
            'uses'      => $controller.'@toggleActive',
            'operation' => 'toggle-active',
        ]);
    }
    public function toggleActive($id){
        if ($this->crud->hasAccess('toggle-active')){
            $entry = $this->crud->model->findOrFail($id);
            $entry->is_active = !$entry->is_active;
            $entry->save();
            $this->afterToggleActive($entry);
            Alert::success(__('crud.operation_success'))->flash();
            return redirect()->back();
        }
        Alert::error(trans('messages.permission denied'))->flash();
        return redirect()->back();

    }
    public function toggleActiveButton(){
        $this->crud->addButtonFromView('line', 'toggleActive', 'toggleActive');
    }

     public function afterToggleActive($entry){

     }
}
