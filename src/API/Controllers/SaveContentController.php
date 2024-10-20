<?php

namespace Jiny\Site\Page\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SaveContentController
{
    public function save(Request $request)
    {
        try {
            // 요청에서 컨텐츠, URI, slot 가져오기
            $content = $request->input('content');
            $uri = $request->input('uri');
            $slot = $request->input('slot');

            // URI에서 시작 슬래시 제거
            $uri = ltrim($uri, '/');

            // 파일 경로 생성
            $filePath = resource_path("www/{$slot}/{$uri}/index.html");

            // 디렉토리가 없으면 생성
            $directory = dirname($filePath);
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // 파일에 컨텐츠 저장 (
            File::put($filePath, $content);

            // 성공 응답에 저장된
            return response()->json([
                'success' => true,
                'message' => '컨텐츠가 성공적으로 저장되었습니다.',
                'content' => $content, // 저장된 컨텐츠를 응답에 포함
                'uri' => $uri,
                'slot' => $slot,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '저장 중 오류가 발생했습니다: ' . $e->getMessage()], 500);
        }
    }
}
