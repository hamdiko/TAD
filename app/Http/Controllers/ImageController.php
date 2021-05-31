<?php

namespace App\Http\Controllers;

use ReflectionClass;
use Illuminate\Support\Str;
use App\Interfaces\HasImages;

class ImageController extends Controller
{
    public function __invoke($entity, $id, $key)
    {
        $class = "App\\Models\\" .  str_replace(" ", "", Str::title(str_replace('-', ' ', $entity)));

        if (class_exists($class) && in_array(HasImages::class, class_implements($class))) {

            $img = $class::findOrFail($id)->getImageData($key);

            return response($img['data'])->header('Content-Type', $img['mime']);
        }

        abort(404);
    }
}
