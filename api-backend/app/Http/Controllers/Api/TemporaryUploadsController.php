<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Models\TemporaryUpload;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class TemporaryUploadsController extends Controller
{
    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
        ]);

        $files = $request->file('files');
        $files = is_array($files) ? $files : [$files];

        $files = DB::transaction(function () use ($files) {
            $uploadedFiles = collect();
            foreach ($files as $file) {
                /** @var TemporaryUpload $temporaryUpload */
                $temporaryUpload = TemporaryUpload::create();
                $temporaryUpload->addMedia($file)
                    ->usingFileName($file->hashName())
                    ->withCustomProperties(['original_file_name' => $file->getClientOriginalName()])
                    ->toMediaCollection();
                $uploadedFiles->push($temporaryUpload->getMedia()->first());
            }
            return $uploadedFiles;
        });

        return response(MediaResource::collection($files), Response::HTTP_CREATED);
    }
}
