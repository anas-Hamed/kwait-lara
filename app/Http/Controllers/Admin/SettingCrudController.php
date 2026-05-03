<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Traits\BilingualFieldsTrait;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SettingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as _update;
    }
    use BilingualFieldsTrait;

    public function setup()
    {
        CRUD::setModel(Setting::class);
        CRUD::setEntityNameStrings(trans('backpack::settings.setting_singular'), trans('backpack::settings.setting_plural'));
        CRUD::setRoute(backpack_url('setting'));
    }

    public function setupListOperation()
    {
        // only show settings which are marked as active
        CRUD::addClause('where', 'active', 1);

        // columns to show in the table view
        CRUD::setColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::settings.name'),
            ],
            [
                'name'  => 'value',
                'label' => trans('backpack::settings.value'),
            ],
            [
                'name'  => 'description',
                'label' => trans('backpack::settings.description'),
            ],
        ]);
    }

    public function setupUpdateOperation()
    {
        $this->crud->setUpdateContentClass('col-md-12');
        CRUD::addField([
            'name'       => 'name',
            'label'      => trans('backpack::settings.name'),
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);

        $field = json_decode(CRUD::getCurrentEntry()->field, true);
        $type  = $field['type'] ?? 'text';
        $label = $field['label'] ?? trans('backpack::settings.value');

        $this->addBilingualField('value', $label, $type);
    }

    public function update()
    {
        $request = $this->crud->getRequest();
        $request->request->add(['value' => $request->input('value_ar')]);

        $response = $this->_update();
        $entry = $this->crud->getCurrentEntry();
        if ($entry) {
            $this->saveBilingualTranslations($entry, ['value']);
        }
        return $response;
    }
}
