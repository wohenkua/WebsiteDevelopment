<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class GenerateImageFromText implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $prompt, $samples, $steps, $force_task_id;  

    //测试用参数基础数据，以后根据需求更改
    public function __construct(string $prompt, int $samples, int $steps, string $force_task_id)
    {
        $this->prompt = $prompt;
        $this->samples = $samples;
        $this->steps = $steps;
        $this->force_task_id = $force_task_id;
    }
   

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $api_url = config('services.stable_diffusion.api_url', '/sdapi/v1/txt2img');
            $response = HTTP::timeout(300)->post("$api_url/sdapi/v1/txt2img", [
                //'prompt' => $this->prompt,
                //'user_id' => $this->userId,
                'samples' => $this->samples ?? 1,
                'steps' => $this->steps ?? 20,
                'force_task_id' => $this->force_task_id,
            ]);
            if($response->successful()){
                $this->saveGeneratedImage($response->json());
            }else{
                Log::error('Image generation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'prompt' => $this->prompt,
                    //'user_id' => $this->userId,
                ]);

                $this->fail(new \Exception('Image generation failed with status ' . $response->status()));

            }
        }catch (\Exception $e) {
            Log::error('Image generation job failed', [
                'error' => $e->getMessage(),
                'prompt' => $this->prompt,
                //'user_id' => $this->userId,
            ]);
            $this->fail($e);
        }
    }

    public function saveGeneratedImage(array $data): void
    {
        // 检测是否包含images字段
        $images = $data['images'] ?? [];
        if(empty($images)){
            Log::warning('No images returned from generation', [
                'prompt' => $this->prompt,
               // 'user_id' => $this->userId,
            ]);
            return;
        }

        foreach ($images as $index => $image) {
            $imageData = base64_decode($image);
            if ($imageData === false) {
                Log::error('Failed to decode image data', [
                    'prompt' => $this->prompt,
                    //'user_id' => $this->userId,
                ]);
                continue;
            }

            $filename = Str::slug($this->prompt) . '-' . now()->timestamp . "-{$index}.png";
            // 存储到公共资源，用户验证做好后转私密
            Storage::disk('public')->put("generated_images/{$filename}", $imageData);
        }
    }
}
