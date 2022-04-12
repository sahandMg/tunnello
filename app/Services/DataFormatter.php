<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 4/10/22
 * Time: 12:23 PM
 */

namespace App\Services;


use App\Repositories\Enum\AppEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class DataFormatter
{
    public static function shapeJsonResponseData($status, string $state, $token = null, $data = [], $expiry = 1000000)
    {
        return [
            'status' => $status,
            'state' => $state,
            'token' => $token,
            'expires_in' => $expiry,
            'data' => $data
        ];
    }

    public static function collectionShaper(Collection $collection, array $fields, $type = AppEnum::SHAPE_ONLY)
    {
        if ($type != AppEnum::SHAPE_ONLY) {
            return $collection->except($fields);
        }
        return $collection->only($fields);
    }

    public static function modelShaper(Model $model, array $fields, $type = AppEnum::SHAPE_ONLY)
    {
        $tmp = [];
        $all_attribute_keys = $model->getAttributes();
        if ($type == AppEnum::SHAPE_Except) {
            foreach ($all_attribute_keys as $key => $attr) {
                if (!in_array($key, $fields)) {
                    $tmp[$key] = $attr;
                }
            }
            return $tmp;
        } else if ($type == AppEnum::SHAPE_ONLY) {
            foreach ($fields as $field) {
                $tmp[$field] = $model->getAttributeValue($field);
            }
            return $tmp;
        }
    }
}