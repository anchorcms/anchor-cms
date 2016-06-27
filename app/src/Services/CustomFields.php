<?php

namespace Anchorcms\Services;

use Anchorcms\Mappers\MapperInterface;
use Psr\Http\Message\ServerRequestInterface;

class CustomFields
{
    protected $fields;

    protected $postmeta;

    protected $pagemeta;

    protected $media;

    public function __construct(array $fields, MapperInterface $postmeta, MapperInterface $pagemeta)
    {
        $this->fields = $fields;
        $this->postmeta = $postmeta;
        $this->pagemeta = $pagemeta;
    }

    protected function getFields(string $type): array
    {
        $query = $this->fields->query();
        $query->where('type = :type')
            ->setParameter('type', $type);

        return $this->fields->fetchAll($query);
    }

    public function getFieldValues(string $type, int $id): array
    {
        $fields = $this->getFields($type);
        $values = [];
        $table = $type.'meta';

        foreach ($fields as $field) {
            $query = $this->$table->query();

            $query->where($type.' = :id')
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

    public function saveFields(ServerRequestInterface $request, array $input, string $type, int $id)
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

    protected function appendTextField($form, $field, array $attributes)
    {
        $input = new \Forms\Elements\Input($field->key, [
            'label' => $field->label,
            'attributes' => $attributes,
        ]);

        $form->addElement($input);
    }

    protected function appendHtmlField($form, $field, array $attributes)
    {
        $input = new \Forms\Elements\Textarea($field->key, [
            'label' => $field->label,
            'attributes' => $attributes,
        ]);

        $form->addElement($input);
    }

    protected function appendImageField($form, $field, array $attributes)
    {
        $input = new \Forms\Elements\File($field->key, [
            'label' => $field->label,
            'attributes' => $attributes,
        ]);

        $form->addElement($input);
    }

    protected function appendFileField($form, $field, array $attributes)
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
