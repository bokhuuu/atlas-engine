<?php

declare(strict_types=1);

namespace App\Modules\AI\Console\Commands;

use App\Modules\AI\Agents\PropertyAssistantAgent;
use Illuminate\Console\Command;

/**
 * Interactive terminal chat with the Atlas AI assistant.
 *
 * Useful for testing the agent, its tools and system prompt during development
 * without opening a browser. Uses the same PropertyAssistantAgent as the web UI,
 * so tool calls, history and prompt versioning all work identically.
 *
 * Usage: php artisan ai:chat
 * Type 'exit' to quit the session.
 */
class AtlasAiChat extends Command
{
    protected $signature = 'ai:chat';

    protected $description = 'Start an interactive chat session with the Atlas AI assistant';

    public function handle(): void
    {
        $this->info('Atlas AI Assistant — type "exit" to quit.');
        $this->newLine();

        $sessionId = 'cli_'.uniqid();
        $agent = new PropertyAssistantAgent($sessionId);

        while (true) {
            $message = $this->ask('You');

            if (strtolower(trim($message)) === 'exit') {
                $this->info('Goodbye.');
                break;
            }

            if (empty(trim($message))) {
                continue;
            }

            try {
                $response = $agent->message($message)->respond();
                $this->newLine();
                $this->line('<fg=green>Assistant:</> '.$response);
                $this->newLine();
            } catch (\Throwable $e) {
                $this->error('Error: '.$e->getMessage());
            }
        }
    }
}
