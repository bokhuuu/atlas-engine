import { Head } from '@inertiajs/react';
import {
    BarChart2,
    Bot,
    DollarSign,
    FileSpreadsheet,
    List,
    PiggyBank,
    Search,
    Send,
    ThumbsDown,
    ThumbsUp,
    TrendingDown,
    Zap,
} from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import AppLayout from '@/layouts/app-layout';
import { formatCost } from '@/lib/format-cost';
import { postJson } from '@/lib/scraper-api';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'AI Assistant', href: '/admin/assistant' },
];

interface Message {
    id: string;
    role: 'user' | 'assistant';
    content: string;
    streaming?: boolean;
}

interface FeedbackState {
    [messageId: string]: 1 | -1 | null;
}

interface UsageStats {
    this_month: { tokens: number; cost_usd: number };
    all_time: { tokens: number; cost_usd: number };
}

const SUGGESTED_QUERIES = [
    {
        icon: BarChart2,
        label: 'Median price/sqm for apartments for sale',
        query: 'What is the median price per sqm for apartments for sale across Yerevan?',
    },
    {
        icon: Search,
        label: 'Cheapest district for commercial rent',
        query: 'Which district has the lowest median price per sqm for commercial space for rent?',
    },
    {
        icon: Zap,
        label: 'Compare Kentron vs Arabkir apartments',
        query: 'Compare the median price per sqm for apartments for sale in Kentron versus Arabkir.',
    },
    {
        icon: List,
        label: 'Find 3-bed apartments in Kentron under $200k',
        query: 'Find 3 bedroom apartments for sale in Kentron under $200,000.',
    },
    {
        icon: FileSpreadsheet,
        label: 'Export commercial listings to Excel',
        query: 'Export all commercial real estate for sale listings to Excel.',
    },
    {
        icon: TrendingDown,
        label: 'Districts with price reductions this week',
        query: 'Which districts had the most price reductions for apartments for sale in the last 7 days?',
    },
    {
        icon: PiggyBank,
        label: 'Best rental yield districts for commercial',
        query: 'What is the estimated rental yield per district for commercial property?',
    },
];

export default function Assistant() {
    const [messages, setMessages] = useState<Message[]>([]);
    const [feedback, setFeedback] = useState<FeedbackState>({});
    const [input, setInput] = useState('');
    const [streaming, setStreaming] = useState(false);
    const [usage, setUsage] = useState<UsageStats | null>(null);
    const [usageFlash, setUsageFlash] = useState(false);

    const bottomRef = useRef<HTMLDivElement>(null);
    const inputRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages]);

    useEffect(() => {
        fetch('/api/v1/ai/history', {
            headers: {
                Accept: 'application/json',
                'X-XSRF-TOKEN': decodeURIComponent(
                    document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '',
                ),
            },
        })
            .then((r) => r.json())
            .then((data) => {
                if (Array.isArray(data.messages) && data.messages.length > 0) {
                    setMessages(
                        data.messages.map(
                            (m: { role: string; content: string }) => ({
                                id: crypto.randomUUID(),
                                role: m.role as 'user' | 'assistant',
                                content: m.content,
                            }),
                        ),
                    );
                }
            })
            .catch(() => {});
    }, []);

    useEffect(() => {
        fetchUsage();
    }, []);

    const sendMessage = async (text: string) => {
        if (!text.trim() || streaming) {
            return;
        }

        const userMessage: Message = {
            id: crypto.randomUUID(),
            role: 'user',
            content: text.trim(),
        };

        const assistantId = crypto.randomUUID();

        const assistantMessage: Message = {
            id: assistantId,
            role: 'assistant',
            content: '',
            streaming: true,
        };

        setMessages((prev) => [...prev, userMessage, assistantMessage]);
        setInput('');
        setStreaming(true);

        try {
            const response = await fetch('/api/v1/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-XSRF-TOKEN': decodeURIComponent(
                        document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '',
                    ),
                },
                body: JSON.stringify({ message: text.trim() }),
            });

            const data = await response.json();

            if (data.error) {
                setMessages((prev) =>
                    prev.map((m) =>
                        m.id === assistantId
                            ? { ...m, content: data.error, streaming: false }
                            : m,
                    ),
                );

                return;
            }

            const fullText: string = data.text ?? '';
            const words = fullText.split(' ');
            let current = '';
            let index = 0;

            const interval = setInterval(() => {
                if (index >= words.length) {
                    clearInterval(interval);
                    setMessages((prev) =>
                        prev.map((m) =>
                            m.id === assistantId
                                ? { ...m, content: fullText, streaming: false }
                                : m,
                        ),
                    );

                    return;
                }

                current += (index === 0 ? '' : ' ') + words[index];
                index++;

                setMessages((prev) =>
                    prev.map((m) =>
                        m.id === assistantId ? { ...m, content: current } : m,
                    ),
                );
            }, 30);
        } catch (err: unknown) {
            if (err instanceof Error && err.name !== 'AbortError') {
                setMessages((prev) =>
                    prev.map((m) =>
                        m.id === assistantId
                            ? {
                                  ...m,
                                  content:
                                      'Something went wrong. Please try again.',
                                  streaming: false,
                              }
                            : m,
                    ),
                );
            }
        } finally {
            setStreaming(false);
            inputRef.current?.focus();
            fetchUsage();
        }
    };

    const handleFeedback = async (
        messageId: string,
        vote: 1 | -1,
        content: string,
    ) => {
        if (feedback[messageId]) {
            return;
        }

        setFeedback((prev) => ({ ...prev, [messageId]: vote }));

        try {
            await postJson('/api/v1/ai/feedback', {
                vote,
                response_text: content.slice(0, 5000),
            });
        } catch {
            // feedback failure is silent
        }
    };

    const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage(input);
        }
    };

    const fetchUsage = async () => {
        try {
            const r = await fetch('/api/v1/ai/usage', {
                headers: {
                    Accept: 'application/json',
                    'X-XSRF-TOKEN': decodeURIComponent(
                        document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '',
                    ),
                },
            });
            const data = await r.json();
            setUsage(data);
            setUsageFlash(true);
            setTimeout(() => setUsageFlash(false), 600);
        } catch {
            // usage fetch failure is silent
        }
    };

    const isEmpty = messages.length === 0;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="AI Assistant" />

            <div className="flex h-[calc(100vh-8rem)] gap-4 p-4 lg:p-6">
                {/* Left panel — suggested queries, vertical, scrollable */}
                <div className="hidden w-56 shrink-0 flex-col gap-2 lg:flex">
                    <p className="text-xs font-medium tracking-wider text-muted-foreground uppercase">
                        Suggested queries
                    </p>
                    <div className="flex flex-col gap-2 overflow-y-auto">
                        {SUGGESTED_QUERIES.map((item) => (
                            <button
                                key={item.query}
                                onClick={() => sendMessage(item.query)}
                                disabled={streaming}
                                className="flex cursor-pointer items-start gap-2.5 rounded-lg border border-border bg-card p-3 text-left transition-colors hover:bg-accent disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <div className="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-primary/10 text-primary">
                                    <item.icon className="h-3.5 w-3.5" />
                                </div>
                                <span className="text-xs leading-snug text-foreground">
                                    {item.label}
                                </span>
                            </button>
                        ))}
                    </div>
                </div>

                {/* Chat panel */}
                <div className="flex flex-1 flex-col overflow-hidden rounded-xl border border-border bg-card">
                    {/* Header */}
                    <div className="flex items-center gap-2.5 border-b border-border px-4 py-3">
                        <div className="flex h-2 w-2 rounded-full bg-emerald-500" />
                        <span className="text-sm font-medium text-foreground">
                            Atlas AI Assistant
                        </span>
                        {usage && (
                            <div
                                className={`ml-auto flex items-center gap-3 transition-all duration-300 ${
                                    usageFlash
                                        ? 'text-primary drop-shadow-[0_0_6px_hsl(var(--primary)/0.6)]'
                                        : 'text-muted-foreground'
                                }`}
                            >
                                <span className="flex items-center gap-1 text-xs">
                                    <Zap className="h-3 w-3" />
                                    <span
                                        className={`tabular-nums transition-all duration-300 ${usageFlash ? 'scale-105' : 'scale-100'}`}
                                    >
                                        {usage.this_month.tokens.toLocaleString()}
                                    </span>
                                    <span className="text-muted-foreground/60">
                                        tokens
                                    </span>
                                </span>
                                <span className="flex items-center gap-1 text-xs">
                                    <DollarSign className="h-3 w-3" />
                                    <span
                                        className={`tabular-nums transition-all duration-300 ${usageFlash ? 'scale-105' : 'scale-100'}`}
                                    >
                                        {formatCost(usage.this_month.cost_usd)}
                                    </span>
                                    <span className="text-muted-foreground/60">
                                        this month
                                    </span>
                                </span>
                            </div>
                        )}
                        {!usage && (
                            <span className="ml-auto text-xs text-muted-foreground">
                                Yerevan market data
                            </span>
                        )}
                    </div>

                    {/* Messages */}
                    <div className="flex-1 overflow-y-auto p-4">
                        {isEmpty ? (
                            <div className="flex h-full flex-col items-center justify-center gap-3">
                                <Bot className="h-10 w-10 text-muted-foreground" />
                                <p className="text-center text-sm text-muted-foreground">
                                    Select a suggested query or type your own
                                    question below
                                </p>
                            </div>
                        ) : (
                            <div className="flex flex-col gap-4">
                                {messages.map((message) => (
                                    <div
                                        key={message.id}
                                        className={`flex flex-col gap-1 ${message.role === 'user' ? 'items-end' : 'items-start'}`}
                                    >
                                        <div
                                            className={`max-w-[90%] rounded-xl px-4 py-2.5 text-sm leading-relaxed lg:max-w-[80%] ${
                                                message.role === 'user'
                                                    ? 'bg-primary text-primary-foreground'
                                                    : 'bg-muted text-foreground'
                                            }`}
                                        >
                                            {message.role === 'assistant' ? (
                                                <div className="max-w-none overflow-x-auto [&_ol]:list-decimal [&_ol]:pl-4 [&_p]:mb-2 [&_strong]:font-bold [&_table]:w-full [&_table]:border-collapse [&_td]:border [&_td]:border-border [&_td]:px-2 [&_td]:py-1 [&_th]:border [&_th]:border-border [&_th]:px-2 [&_th]:py-1 [&_ul]:list-disc [&_ul]:pl-4">
                                                    <ReactMarkdown
                                                        remarkPlugins={[
                                                            remarkGfm,
                                                        ]}
                                                        components={{
                                                            a: ({
                                                                href,
                                                                children,
                                                            }) => (
                                                                <a
                                                                    href={href}
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    className="cursor-pointer text-blue-400 underline hover:text-blue-300"
                                                                >
                                                                    {children}
                                                                </a>
                                                            ),
                                                        }}
                                                    >
                                                        {message.content}
                                                    </ReactMarkdown>
                                                    {message.streaming && (
                                                        <span className="ml-1 inline-flex gap-0.5">
                                                            <span className="h-1.5 w-1.5 animate-bounce rounded-full bg-current [animation-delay:0ms]" />
                                                            <span className="h-1.5 w-1.5 animate-bounce rounded-full bg-current [animation-delay:150ms]" />
                                                            <span className="h-1.5 w-1.5 animate-bounce rounded-full bg-current [animation-delay:300ms]" />
                                                        </span>
                                                    )}
                                                </div>
                                            ) : (
                                                message.content
                                            )}
                                        </div>

                                        {message.role === 'assistant' &&
                                            !message.streaming &&
                                            message.content && (
                                                <div className="flex items-center gap-1.5 px-1">
                                                    <button
                                                        onClick={() =>
                                                            handleFeedback(
                                                                message.id,
                                                                1,
                                                                message.content,
                                                            )
                                                        }
                                                        disabled={
                                                            !!feedback[
                                                                message.id
                                                            ]
                                                        }
                                                        className={`rounded p-1 transition-colors ${
                                                            feedback[
                                                                message.id
                                                            ] === 1
                                                                ? 'text-emerald-500'
                                                                : 'text-muted-foreground hover:text-foreground'
                                                        }`}
                                                    >
                                                        <ThumbsUp className="h-3.5 w-3.5 cursor-pointer" />
                                                    </button>
                                                    <button
                                                        onClick={() =>
                                                            handleFeedback(
                                                                message.id,
                                                                -1,
                                                                message.content,
                                                            )
                                                        }
                                                        disabled={
                                                            !!feedback[
                                                                message.id
                                                            ]
                                                        }
                                                        className={`rounded p-1 transition-colors ${
                                                            feedback[
                                                                message.id
                                                            ] === -1
                                                                ? 'text-red-500'
                                                                : 'text-muted-foreground hover:text-foreground'
                                                        }`}
                                                    >
                                                        <ThumbsDown className="h-3.5 w-3.5 cursor-pointer" />
                                                    </button>
                                                </div>
                                            )}
                                    </div>
                                ))}
                                <div ref={bottomRef} />
                            </div>
                        )}
                    </div>

                    {/* Mobile suggested queries — horizontal scroll */}
                    <div className="border-t border-border px-3 pt-3 lg:hidden">
                        <div className="flex gap-2 overflow-x-auto pb-2">
                            {SUGGESTED_QUERIES.map((item) => (
                                <button
                                    key={item.query}
                                    onClick={() => sendMessage(item.query)}
                                    disabled={streaming}
                                    className="flex min-w-40 shrink-0 cursor-pointer items-start gap-2 rounded-lg border border-border bg-muted p-2 text-left disabled:opacity-50"
                                >
                                    <item.icon className="mt-0.5 h-3.5 w-3.5 shrink-0 text-primary" />
                                    <span className="text-xs leading-snug text-foreground">
                                        {item.label}
                                    </span>
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Input */}
                    <div className="border-t border-border p-3">
                        <div className="flex items-center gap-2 rounded-lg border border-border bg-background px-3 py-2">
                            <input
                                ref={inputRef}
                                type="text"
                                value={input}
                                onChange={(e) => setInput(e.target.value)}
                                onKeyDown={handleKeyDown}
                                placeholder="Ask anything about the Yerevan market…"
                                disabled={streaming}
                                className="flex-1 bg-transparent text-sm text-foreground outline-none placeholder:text-muted-foreground disabled:opacity-50"
                            />
                            <button
                                onClick={() => sendMessage(input)}
                                disabled={streaming || !input.trim()}
                                className="flex h-7 w-7 shrink-0 cursor-pointer items-center justify-center rounded-md bg-primary text-primary-foreground transition-opacity disabled:opacity-40"
                            >
                                <Send className="h-3.5 w-3.5" />
                            </button>
                        </div>
                        <p className="mt-2 px-1 text-xs text-muted-foreground">
                            Answers are based on live scraped data from list.am
                            only.
                        </p>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
