<?php
namespace App\Traits;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;
/**
 * @property-read CrudPanel $crud
 */
trait ToggleFeaturedOperation{
    protected function setupToggleFeaturedDefaults()
    {
        $this->crud->allowAccess(['toggle-featured']);
        $this->crud->operation('list', function () {
            $this->toggleFeaturedButton();
        });

        $this->crud->operation('show', function () {
            $this->toggleFeaturedButton();
        });
    }
    protected function setupToggleFeaturedRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/toggle-featured', [
            'as'        => $routeName.'.toggleFeatured',
            'uses'      => $controller.'@toggleFeatured',
            'operation' => 'toggle-featured',
        ]);
    }

    public function toggleFeatured($id){
        if ($this->crud->hasAccess('toggle-featured')){
            $entry = $this->crud->model->findOrFail($id);
            $entry->is_featured = !$entry->is_featured;
            $entry->save();
            Alert::success(trans('messages.success'))->flash();
            return redirect()->back();
        }
        Alert::error(trans('messages.permission denied'))->flash();
        return redirect()->back();
    }

    public function toggleFeaturedButton(){
        if (!file_exists(resource_path('views/vendor/backpack/crud/buttons/toggleFeatured.blade.php'))){
            throw new \Exception('make sure you implement toggleFeatured button in resources/views/vendor/backpack/crud/buttons');
        }
        $this->crud->addButtonFromView('line', 'toggleFeatured', 'toggleFeatured','end');
    }
}
