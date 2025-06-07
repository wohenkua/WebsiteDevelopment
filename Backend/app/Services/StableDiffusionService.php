<?php


namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


/**
    * StableDiffusionService
    * 
    * 该服务类用于与Stable Diffusion WebUI API进行交互，主要功能是生成图像。目前没有使用，预留class以便后续扩展。
    * 
    * @package App\Services
*/
class StableDiffusionService{
    protected $api_url =  "http://host.docker.internal:7860";

    public function generateImageFromText(array $params):array{
        $response = HTTP::timeout(99999)->post("{$this->api_url}/sdapi/v1/txt2img", $params);
        if($response->failed()){
            Log::error('SD WebUI API error', ['error' => $response->body()]);
            throw new \Exception("API request failed: " . $response->status());
        }

        return [
             'task_id' => $response->json('task_id'),
            'images' => $response->json('images', []),
            'parameters' => $response->json('parameters'),
            'info' => $response->json('info')
        ];
    }

}