<?php

namespace Anchorcms\Services;

class CustomFields
{

    protected $fields;

    protected $postmeta;

    protected $pagemeta;

    protected $media;

    public function __construct($fields, $postmeta, $pagemeta)
    {
        $this->fields = $fields;
        $this->postmeta = $postmeta;
        $this->pagemeta = $pagemeta;
    }

    protected function getFields($type)
    {
        $query = $this->fields->query();
        $query->where('type = :type')
            ->setParameter('type', $type);
        return $this->fields->fetchAll($query);
    }

    public function getFieldValues($type, $id)
    {
        $fields = $this->getFields($type);
        $values = [];
        $table = $type.'meta';

        foreach ($fields as $field) {
            $query = $this->$table->query();

            $query->where($type . ' = :id')
                    ->setParameter('id', $id)
                ->where('custom_field = :custom_field')
                    ->setParameter('custom_field', $field->id);

            $meta = $this->db->fetchAssoc($query->getSQL(), $query->getParameters());

            if ($meta) {
                $values[$field->key] = json_decode($meta->data, true);
            }
        }

        return $values;
    }

    public function saveFields($request, array $input, $type, $id)
    {
        $fields = $this->getFields($type);
        $table = $type.'meta';

        $files = $request->getUploadedFiles();

        foreach ($fields as $field) {
            if (false === array_key_exists($field->key, $input)) {
                continue;
            }

            if (null === $input[$field->key] && array_key_exists($field->key, $files)) {
                $result = $this->media->upload($files[$field->key]);

                $value = json_encode($result);
            } else {
                $value = json_encode($input[$field->key]);
            }

            $meta = $this->$table->where($type, '=', $id)
                ->where('custom_field', '=', $field->id)
                ->fetch();

            if ($meta) {
                $meta->data = $value;
                $this->$table->save($meta);
            } else {
                $this->$table->insert([
                    $type => $id,
                    'custom_field' => $field->id,
                    'data' => $value,
                ]);
            }
        }
    }

    protected function appendTextField($form, $field, $attributes)
    {
        $input = new \Forms\Elements\Input($field->key, [
            'label' => $field->label,
            'attributes' => $attributes,
        ]);

        $form->addElement($input);
    }

    protected function appendHtmlField($form, $field, $attributes)
    {
        $input = new \Forms\Elements\Textarea($field->key, [
            'label' => $field->label,
            'attributes' => $attributes,
        ]);

        $form->addElement($input);
    }

    protected function appendImageField($form, $field, $attributes)
    {
        $input = new \Forms\Elements\File($field->key, [
            'label' => $field->label,
            'attributes' => $attributes,
        ]);

        $form->addElement($input);
    }

    protected function appendFileField($form, $field, $attributes)
    {
        $input = new \Forms\Elements\File($field->key, [
            'label' => $field->label,
            'attributes' => $attributes,
        ]);

        $form->addElement($input);
    }

    public function appendFields($form, $type)
    {
        $fields = $this->getFields($type);

        foreach ($fields as $field) {
            $attributes = json_decode($field->attributes, true) ?: [];
            $method = sprintf('append%sField', ucfirst($field->field));
            $this->{$method}($form, $field, $attributes);
            $form->pushFilter($field->key, FILTER_UNSAFE_RAW);
        }
    }
}
