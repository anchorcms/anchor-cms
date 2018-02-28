<?php

use System\arr;
use System\database\query;
use System\input;
use System\uri;

/**
 * extend class
 *
 * @property \stdClass $attributes
 * @property \stdClass $value
 * @property string    $key
 * @property string    $field
 */
class extend extends Base
{
    public static $table = 'extend';

    /**
     * Holds all extend types
     *
     * @var array
     */
    public static $types = [
        'post'     => 'post',
        'page'     => 'page',
        'category' => 'category',
        'user'     => 'user'
    ];

    /**
     * Holds all field types
     *
     * @var array
     */
    public static $field_types = [
        'text'   => 'text',
        'html'   => 'html',
        'image'  => 'image',
        'file'   => 'file',
        'toggle' => 'toggle'
    ];

    /**
     * Retrieves a field
     *
     * @param string $type field type
     * @param string $key  field key
     * @param int    $id   field ID
     *
     * @return \stdClass
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function field($type, $key, $id = -1)
    {
        $field = Query::table(static::table())
                      ->where('type', '=', $type)
                      ->where('key', '=', $key)
                      ->fetch();

        if ($field) {
            $meta = Query::table(static::table($type . '_meta'))
                         ->where($type, '=', $id)
                         ->where('extend', '=', $field->id)
                         ->fetch();

            $field->value = Json::decode($meta ? $meta->data : '{}');
        }

        return $field;
    }

    /**
     * Retrieves a field value
     *
     * @param \extend|\stdClass $extend extend to retrieve a value from
     * @param mixed|null        $value  (optional) fallback value
     *
     * @return mixed|null field value
     */
    public static function value($extend, $value = null)
    {
        switch ($extend->field) {
            case 'text':
                if ( ! empty($extend->value->text)) {
                    $value = $extend->value->text;
                }
                break;

            case 'html':
                if ( ! empty($extend->value->html)) {
                    $value = parse($extend->value->html);
                }
                break;

            case 'toggle':
                if ( ! empty($extend->value->toggle)) {
                    $value = $extend->value->toggle;
                }
                break;

            case 'image':
            case 'file':
                if ( ! empty($extend->value->filename)) {
                    $value = asset('content/' . $extend->value->filename);
                }
                break;
        }

        return $value;
    }

    /**
     * Generates the extend HTML form
     *
     * @param \extend|\stdClass $item
     *
     * @return string
     */
    public static function html($item)
    {
        switch ($item->field) {
            case 'text':
                $value = isset($item->value->text) ? $item->value->text : '';
                $html  = '<input id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="text" value="' . htmlentities($value) . '">';
                break;

            case 'html':
                $value = isset($item->value->html) ? $item->value->html : '';
                $html  = '<textarea id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="text">' . $value . '</textarea>';
                break;

            case 'toggle':
                $value = isset($item->value->toggle) ? $item->value->toggle : 0;
                $html  = Form::checkbox('extend[' . $item->key . ']', 1, $value, ['id' => 'extend_' . $item->key]);
                break;

            case 'image':
            case 'file':
                $value = isset($item->value->filename) ? $item->value->filename : '';

                $html = '<span class="current-file">';

                if ($value) {
                    $html .= '<a href="' . asset('content/' . $value) . '" target="_blank">' . $value . '</a>';
                }

                $html .= '</span>
					<span class="file">
					<input id="extend_' . $item->key . '" name="extend[' . $item->key . ']" type="file">
					<input type="hidden" name="extend[' . $item->key . ']" value="' . asset('content/' . $value) . '">
					</span>';

                if ($value) {
                    $html .= '</p><p>
					<label>' . __('global.delete') . ' ' . $item->label . ':</label>
					<input type="checkbox" name="extend_remove[' . $item->key . ']" value="1">';
                }

                break;

            default:
                $html = '';
        }

        return $html;
    }

    /**
     * Paginates the extend list
     *
     * @param int $page    page offset
     * @param int $perpage page limit
     *
     * @return \Paginator
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function paginate($page = 1, $perpage = 10)
    {
        $query   = Query::table(static::table());
        $count   = $query->count();
        $results = $query
            ->take($perpage)
            ->skip(($page - 1) * $perpage)
            ->get();

        return new Paginator($results, $count, $page, $perpage, Uri::to('admin/extend/fields'));
    }

    /**
     * Handles an image extend field
     *
     * @param \extend $extend
     *
     * @return string
     * @throws \ErrorException
     */
    public static function process_image($extend)
    {
        $file = Arr::get(static::files(), $extend->key);

        if ($file and $file['error'] === UPLOAD_ERR_OK) {
            $name = basename($file['name']);

            // TODO: Unused variable. What is it for?
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

            if ($filepath = static::upload($file)) {
                $filename = basename($filepath);
                self::resizeImage($extend, $filepath);
            }
        }

        // Handle images which have been uploaded indirectly not as files.
        $image_upload = Input::get('extend.' . $extend->key);
        if ($image_upload) {
            $image_upload = str_replace("\\", '/', $image_upload);
            $filename     = basename($image_upload);
            $name         = $filename;
        }

        $data = compact('name', 'filename');

        return Json::encode($data);
    }

    /**
     * Retrieves a list of all uploaded files
     *
     * @return array
     */
    public static function files()
    {
        // format file array
        $files = [];

        if (isset($_FILES['extend'])) {
            foreach ($_FILES['extend'] as $label => $items) {
                foreach ($items as $key => $value) {
                    $files[$key][$label] = $value;
                }
            }
        }

        return $files;
    }

    /**
     * Uploads a file
     *
     * @param array $file file meta data
     *
     * @return string
     * @throws \ErrorException
     */
    public static function upload($file)
    {
        $uploader = new Uploader(PATH . 'content', ['png', 'jpg', 'bmp', 'gif', 'pdf']);
        $filepath = $uploader->upload($file);

        return $filepath;
    }

    /**
     * Resizes an image
     *
     * @param \extend $extend
     * @param string  $filepath path to the image file
     *
     * @return void
     */
    private static function resizeImage($extend, $filepath)
    {
        // resize image
        if (isset($extend->attributes->size->width) and isset($extend->attributes->size->height)) {
            $image = Image::open($filepath);

            $width  = intval($extend->attributes->size->width);
            $height = intval($extend->attributes->size->height);

            // resize larger images
            if (
                ($width and $height) and
                ($image->width() > $width or $image->height() > $height)
            ) {
                $image->resize($width, $height);

                // TODO: Missing ext. Might that be the unused variable above?
                $image->output($ext, $filepath);
            }
        }
    }

    /**
     * Handles a file field
     *
     * @param \extend $extend
     *
     * @return string
     * @throws \ErrorException
     */
    public static function process_file($extend)
    {
        $file = Arr::get(static::files(), $extend->key);

        if ($file and $file['error'] === UPLOAD_ERR_OK) {
            $name = basename($file['name']);

            if ($filepath = static::upload($file)) {
                $filename = basename($filepath);

                return Json::encode(compact('name', 'filename'));
            }
        }
    }

    /**
     * Handles a text field
     *
     * @param \extend $extend
     *
     * @return string
     */
    public static function process_text($extend)
    {
        $text = Input::get('extend.' . $extend->key);

        return Json::encode(compact('text'));
    }

    /**
     * Handles an HTML field
     *
     * @param \extend $extend
     *
     * @return string
     */
    public static function process_html($extend)
    {
        $html = Input::get('extend.' . $extend->key);

        return Json::encode(compact('html'));
    }

    /**
     * Handles a toggle field
     *
     * @param \extend $extend
     *
     * @return string
     */
    public static function process_toggle($extend)
    {
        $toggle = Input::get('extend.' . $extend->key);

        return Json::encode(['toggle' => (int)$toggle]);
    }

    /**
     * Save all extend fields
     *
     * @param string $type field type
     * @param int    $item field item
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function process($type, $item)
    {
        foreach (static::fields($type, $item) as $extend) {
            if ($extend->attributes) {
                $extend->attributes = Json::decode($extend->attributes);
            }

            $data = call_user_func_array(['Extend', 'process_' . $extend->field], [$extend, $item]);

            // save data
            if ( ! is_null($data) and $data != '[]') {
                $table = static::table($extend->type . '_meta');
                $query = Query::table($table)
                              ->where('extend', '=', $extend->id)
                              ->where($extend->type, '=', $item);

                if ($query->count()) {
                    $query->update(['data' => $data]);
                } else {
                    $query->insert([
                        'extend'      => $extend->id,
                        $extend->type => $item,
                        'data'        => $data
                    ]);
                }
            }

            // remove data
            if (Input::get('extend_remove.' . $extend->key)) {
                if (isset($extend->value->filename) and strlen($extend->value->filename)) {
                    Query::table(static::table($extend->type . '_meta'))
                         ->where('extend', '=', $extend->id)
                         ->where($extend->type, '=', $item)->delete();

                    $resource = PATH . 'content' . DS . $extend->value->filename;
                    file_exists($resource) and unlink(PATH . 'content' . DS . $extend->value->filename);
                }
            }
        }
    }

    /**
     * Fetch all extend fields
     *
     * @param string      $type
     * @param int         $id       (optional)
     * @param string|null $pageType (optional)
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function fields($type, $id = -1, $pageType = null)
    {
        if (is_null($pageType)) {
            $fields = Query::table(static::table())
                           ->where('type', '=', $type)
                           ->get();
        } else {
            $fields = Query::table(static::table())
                           ->where_in('pagetype', [$pageType, 'all'])
                           ->where('type', '=', $type)
                           ->get();
        }

        foreach (array_keys($fields) as $index) {
            $meta = Query::table(static::table($type . '_meta'))
                         ->where($type, '=', $id)
                         ->where('extend', '=', $fields[$index]->id)
                         ->fetch();

            $fields[$index]->value = Json::decode($meta ? $meta->data : '{}');
        }

        return $fields;
    }
}
