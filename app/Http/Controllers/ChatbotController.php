<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private $groqApiKey;
    private $groqApiUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->groqApiKey = env('GROQ_API_KEY');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        $systemPrompt = <<<PROMPT
Bạn là VietGo AI - trợ lý du lịch thông minh của VietGo, chuyên gợi ý tour du lịch tại Việt Nam.

Nhiệm vụ của bạn:
1. Gợi ý các địa điểm du lịch nổi bật, hấp dẫn tại địa phương khách hàng muốn đến.
2. Giới thiệu các hoạt động, trải nghiệm đặc sắc tại điểm đến.
3. Đề xuất thời điểm lý tưởng trong năm để đi du lịch.
4. Gợi ý ẩm thực đặc sản địa phương.
5. Tư vấn các loại hình tour phù hợp (gia đình, cặp đôi, nhóm bạn, v.v.).
6. Cung cấp thông tin hữu ích về văn hóa, phong tục địa phương.

Phong cách trả lời:
- Nhiệt tình, thân thiện và chuyên nghiệp.
- Trả lời bằng tiếng Việt.
- Sử dụng emoji phù hợp để tạo cảm giác sinh động.
- Câu trả lời chi tiết nhưng dễ đọc, có cấu trúc rõ ràng.
- Luôn khuyến khích khách hàng đặt tour qua VietGo.
- Nếu câu hỏi không liên quan đến du lịch, hãy lịch sự hướng khách trở lại chủ đề du lịch.

Lưu ý: Chỉ gợi ý các điểm đến trong lãnh thổ Việt Nam.
PROMPT;

        // Build messages array with history
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add conversation history (max last 10 exchanges to save tokens)
        $recentHistory = array_slice($history, -10);
        foreach ($recentHistory as $item) {
            if (isset($item['role']) && isset($item['content'])) {
                $messages[] = [
                    'role' => $item['role'],
                    'content' => $item['content'],
                ];
            }
        }

        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->groqApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->groqApiUrl, [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => $messages,
                'max_tokens' => 1024,
                'temperature' => 0.8,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $assistantMessage = $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi không thể trả lời lúc này. Vui lòng thử lại.';

                return response()->json([
                    'success' => true,
                    'message' => $assistantMessage,
                ]);
            } else {
                \Log::error('Groq API error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'Xin lỗi, dịch vụ AI tạm thời gián đoạn. Vui lòng thử lại sau.',
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Chatbot exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, đã xảy ra lỗi kết nối. Vui lòng thử lại.',
            ], 500);
        }
    }
}
