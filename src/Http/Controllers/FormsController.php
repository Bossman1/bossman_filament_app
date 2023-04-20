<?php
namespace BossmanFilamentApp\Http\Controllers;

use BossmanFilamentApp\Models\FormContentObject;
use BossmanFilamentApp\Models\FormCustomPage;
use BossmanFilamentApp\Models\FormObjectModel;
use BossmanFilamentApp\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;

class FormsController extends BaseController
{
    private $requestRecord = [];

    public static function startForm($model)
    {
        if ($model->getForm) {
            return view(self::templatePath().'pages.content_object.forms.fields.form', ['form' => $model->getForm])->render();
        }
    }

    public static function endForm($model)
    {
        if ($model->getForm) {
            return '</form>';
        }
    }


    public static function form($model, $identifier, ...$args)
    {
        if(!$model->getForm){
            return  '';
        }
        $form = $model->getForm->formField($identifier);
        $arguments = isset($args[0]) ? (object)$args[0] : [];
        $attributes = $arguments->attributes ?? [];
        $form = (object)array_merge((array)$form, (array)$model->getForm);
        $required = $model->getForm->isRequired($form);
        return view(self::templatePath().'pages.content_object.forms.fields.' . $form->object_type_key, [
            'key' => $identifier,
            'form' => $form,
            'argument' => $arguments,
            'options' => (object)['required' => $required],
            'attributes' => $attributes
        ])->render();
    }


    public static function submitBtn($model, ...$args)
    {
        if(!$model->getForm){ return  ''; }
        $arguments = isset($args[0]) ? (object)$args[0] : [];
        $attributes = $arguments->attributes ?? [];
        return view(self::templatePath().'pages.content_object.forms.fields.submit-button', ['model' => $model, 'argument' => $arguments, 'attributes' => $attributes])->render();
    }


    public function submitForm(Request $request)
    {

        if ($request->has('content_object_id')) {
            $modelFormObjectContent = FormContentObject::with('form_custom_page')->where('id', $request->post('content_object_id'))->first();
            $this->fetchRequest($request);


            $formCustomPageRecords = $modelFormObjectContent->form_custom_page->content;


            $checkValidation = [];
            $validationMessages = [];
            $formContent = [];
            foreach ($this->requestRecord as $key => $value) {
                if (isset($formCustomPageRecords[$key])) {
                    $formObjectId = $formCustomPageRecords[$key]['form_object_id'];
                    $formObjectLabel = $formCustomPageRecords[$key]['label'];
                    $formObjectType = $formCustomPageRecords[$key]['object_type_key'];

                    $args = [
                        'id' => $formObjectId,
                        'label' => $formObjectLabel,
                    ];

                    $options = $this->getFieldOptions($args);

                    if ($options['required']) {
                        $this->formatErrorMessages($key);
                        $checkValidation[$formObjectType . '___' . $key] = 'required';
                        $validationMessages[$formObjectType . '___' . $key] = __('Field') . ' <span style="font-weight: bold; font-style: italic;">' . $this->formatErrorMessages($key) . '</span> ' . __('is required');
                    }
                }

                $formContent[$this->formatErrorMessages($key)] = $value;

            }
            $request->validate($checkValidation, $validationMessages);
            if ($modelFormObjectContent) {
                if ($modelFormObjectContent->collect_data) { // collect data
                    $this->fetchRequest($request); // store form request data in to $this->requestRecord variable
                    $this->storeFormData($request->post('content_object_id')); // return id
                }
                if ($modelFormObjectContent->send_email) { // send emails
                    $emails = $modelFormObjectContent->emails;
                    $details = [
                        'title' => 'Mail from ' . env('APP_NAME'),
                        'body' => 'This is for testing email using smtp',
                        'mailView' => self::templatePath().'emails.default'
                    ];
                    if (!empty($emails)) {
                        Mail::to($emails)->send(new \BossmanFilamentApp\Mail\SendMail($details, $formContent));
                    }

                }
            }
            return back()->withInput()->with(['success' => __('Email was sent successfully')]);
        }


    }

    private function formatErrorMessages($fieldName)
    {
        $name = explode('|', $fieldName);
        return $name[1] ? ucfirst($name[1]) : '';
    }

    private function getFieldOptions(...$args)
    {

        $options = [];
        $arguments = $args[0];
        $modelFormObject = FormObjectModel::where('id', $arguments['id'])->first();

        foreach ($modelFormObject->content as $item) {
            if ($item['title'] == $arguments['label']) {
                $options['required'] = false;
                if (is_array($item['field_options']) && in_array('required', $item['field_options'])) {
                    $options['required'] = true;
                }
            }
        }
        return $options;
    }

    private function fetchRequest($request)
    {
        foreach ($request->all() as $key => $req) {
            $getKey = explode('___', $key);
            if (isset($getKey[1])) {
                $this->requestRecord[$getKey[1]] = $req;
            }
        }
        return $this->requestRecord;
    }

    public function storeFormData($id)
    {
        $modelContentObject = FormContentObject::query()->find($id);
        $suffix = '-Copy';
        if ($modelContentObject) {
            $old_name = $modelContentObject->name . $suffix;
            $old_slug = $modelContentObject->slug . '-' . Str::slug($suffix);
            $old_id = $modelContentObject->id;
        }
        $cloneModelContentObject = $modelContentObject->replicate();
        $cloneModelContentObject->name = $old_name;
        $cloneModelContentObject->slug = $old_slug;
        $cloneModelContentObject->form_content_object_id = $old_id;
        $cloneModelContentObject->created_at = now();
        $cloneModelContentObject->updated_at = now();
        if ($cloneModelContentObject->save()) {
            $modelCustomPage = FormCustomPage::where('form_content_object_id', $old_id)->first();
            if ($modelCustomPage) {
                $cloneModelCustomPage = $modelCustomPage->replicate();
                $cloneModelCustomPage->form_content_object_id = $cloneModelContentObject->id;
                $dataWithTranslation = $this->setData($cloneModelCustomPage->content);
                $cloneModelCustomPage->setTranslations('content', $dataWithTranslation);
                $cloneModelCustomPage->save();
            }
        }
        return $cloneModelContentObject->id;
    }


    private function setData($array)
    {
        $languages = Language::query()->select('key')->get();
        $translations = [];
        foreach ($languages as $language) {
            foreach ($this->requestRecord as $identifier => $value) {
                if (isset($array[$identifier])) {
                    $array[$identifier]['value'] = $value;
                    $translations[$language->key] = $array;
                }
            }
        }
        return $translations;
    }


}
