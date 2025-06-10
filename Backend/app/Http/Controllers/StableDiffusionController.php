<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use App\Services\StableDiffusionService;
use App\Jobs\GenerateImageFromText;

class StableDiffusionController extends Controller
{
    protected StableDiffusionService $sdService;
    public function __construct(StableDiffusionService $sdService)
    {
        $this->sdService = $sdService;
    }

    public function generate(Request $request)
    {
     //TODO: 验证请求参数


     
     
     // 生成task_id用以跟踪队列进度
     $task_id = Str::uuid()->toString();

        // 发送到队列排队生成
        GenerateImageFromText::dispatch(
            $request->input('prompt'),

            $request->input('samples', 1),
            $request->input('steps', 20),
            $request->input('force_task_id', $task_id)

        );
        return response()->json([
            'message' => 'Image generation job has been dispatched.',
            'task_id' => "$task_id"
        ], 202);
    }

    /**
     * 获取生成状态     * 
     * 
     */
    public function status(Request $request){
        $task_id = $request->input('task_id');
        $id_live_previerw = -1;
        $live_preview = true;
        if (!$task_id) {
            return response()->json(['error' => 'Task ID is required'], 400);
        }
        // 检查任务状态
        $response = HTTP::POST("http://host.docker.internal:7860/internal/progress", [
            'task_id' => $task_id,
            'id_live_previerw' => $id_live_previerw,
            'live_preview' => $live_preview
        ])->throw()->json();

        return $response;
    }



    /**
     * 测试临时方法，用于处理Base64编码的图像数据。以后会专用ImageService类处理图像相关服务
     */
    public function createBase64Image($image)
    {
        $encoded_image = 'data:image/png;base64, ' . $image;
        $image_name = Str::uuid() . '.png';
        /*   return $image_name;
        return $image_name; */
        Storage::disk('public')->put($image_name, base64_decode($image));
        $file_path = Storage::disk('public')->path($image_name);
        $file_url = Storage::disk('public')->url($image_name);
        $file_size = Storage::disk('public')->size($image_name);
        $file_mime_type = Storage::disk('public')->mimeType($image_name);
        $file_last_modified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($image_name))->toDateTimeString();

        return response()->json([
            'name' => $image_name,
            'path' => $file_path,
            'url' => $file_url,
            'size' => $file_size,
            'mime_type' => $file_mime_type,
            'last_modified' => $file_last_modified
        ]);
    }
}
