<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Speech\V1\SpeechClient;
use OpenAI\OpenAI;

class WhatsAppAudioController
{
    private $speechClient;
    private $openAiClient;

    public function __construct()
    {
        $this->speechClient = new SpeechClient();
        $this->openAiClient = new OpenAI();
    }

    public function handleAudio(Request $request)
    {
        $audioFile = $request->file('audio');
        $audioPath = $audioFile->store('audio');

        try {
            // Transcribe audio using Google Cloud Speech-to-Text
            $transcription = $this->transcribeAudio($audioPath);

            // Generate AI response
            $aiResponse = $this->generateAIResponse($transcription);

            return response()->json([
                'transcription' => $transcription,
                'response' => $aiResponse,
            ]);
        } catch (\Exception $e) {
            Log::error('Audio handling error: ' . $e->getMessage());
            return response()->json(['error' => 'Audio processing failed.'], 500);
        }
    }

    private function transcribeAudio($audioPath)
    {
        $audioData = file_get_contents(storage_path('app/' . $audioPath));
        $response = $this->speechClient->recognize(
            [
                'config' => [
                    'encoding' => 'LINEAR16',
                    'sampleRateHertz' => 16000,
                    'languageCode' => 'en-US',
                ],
                'audio' => [
                    'content' => $audioData,
                ],
            ]
        );

        $transcription = '';
        foreach ($response->getResults() as $result) {
            $transcription .= $result->getAlternatives()[0]->getTranscript() . ' ';
        }

        return trim($transcription);
    }

    private function generateAIResponse($transcription)
    {
        // Use OpenAI API to get a response based on the transcription
        $response = $this->openAiClient->complete([
            'model' => 'text-davinci-003',
            'prompt' => $transcription,
            'max_tokens' => 150,
        ]);

        return $response['choices'][0]['text'];
    }
}