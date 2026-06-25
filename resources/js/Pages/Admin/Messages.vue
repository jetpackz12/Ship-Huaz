<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    threads: { type: Array, default: () => [] },
    auth: { type: Object, default: () => ({}) },
});

// ── Static data (swap for props.threads later) ──────────────────────────────
const mockThreads = ref([
    {
        id: 1,
        type: 'booking_reminder',
        read: false,
        client: 'Juan dela Cruz',
        ref: 'BSH-2025-0042',
        subject: 'Booking Reminder – June 17',
        date: 'Jun 16',
        time: '08:00 AM',
        messages: [
            {
                id: 'm1',
                from: 'admin',
                name: 'System',
                body: "Reminder sent to client: booking tomorrow, June 17 at 2:00 PM – Captain's Experience Package for 4 guests.",
                time: '08:00 AM',
            },
        ],
        canReply: false,
    },
    {
        id: 2,
        type: 'message',
        read: false,
        client: 'Juan dela Cruz',
        ref: 'BSH-2025-0042',
        subject: 'Action Required: Confirm Your Booking',
        date: 'Jun 15',
        time: '2:30 PM',
        messages: [
            {
                id: 'm1',
                from: 'admin',
                name: 'Captain Rodel',
                body: 'Good day! Please confirm your booking scheduled for tomorrow, June 17, 2025 at 2:00 PM. Reply CONFIRM to secure your slot, or CANCEL if plans have changed.',
                time: '2:30 PM',
            },
            {
                id: 'm2',
                from: 'admin',
                name: 'Captain Rodel',
                body: "We've reserved the upper deck for your group. Free coffee and juice will be ready upon arrival.",
                time: '2:45 PM',
            },
            {
                id: 'm3',
                from: 'client',
                name: 'Juan dela Cruz',
                body: 'CONFIRM! Thank you so much, see you tomorrow!',
                time: '3:10 PM',
            },
        ],
        canReply: true,
    },
    {
        id: 3,
        type: 'booking_confirmed',
        read: true,
        client: 'Maria Santos',
        ref: 'BSH-2025-0038',
        subject: 'Booking Confirmed – Overnight Stay',
        date: 'Jun 10',
        time: '9:15 AM',
        messages: [
            {
                id: 'm1',
                from: 'admin',
                name: 'System',
                body: 'Booking confirmed for Overnight Stay Package on June 10, 2025. Reference: BSH-2025-0038.',
                time: '9:15 AM',
            },
        ],
        canReply: false,
    },
    {
        id: 4,
        type: 'message',
        read: true,
        client: 'Maria Santos',
        ref: 'BSH-2025-0038',
        subject: 'Welcome Aboard!',
        date: 'Jun 8',
        time: '11:00 AM',
        messages: [
            {
                id: 'm1',
                from: 'admin',
                name: 'The Butal Ship Hauz Team',
                body: 'Thank you for choosing The Butal Ship Hauz! Check-in starts at 3:00 PM. Complimentary refreshments are served at the upper deck.',
                time: '11:00 AM',
            },
            {
                id: 'm2',
                from: 'client',
                name: 'Maria Santos',
                body: 'Thank you! Can we request an early check-in?',
                time: '12:30 PM',
            },
            {
                id: 'm3',
                from: 'admin',
                name: 'Captain Rodel',
                body: 'Sure! We can accommodate early check-in by 1:00 PM at no extra charge. See you soon!',
                time: '1:10 PM',
            },
        ],
        canReply: true,
    },
]);

// ── State ────────────────────────────────────────────────────────────────────
const activeTab    = ref('all');
const activeThread = ref(null);
const isCompose    = ref(false);
const replyText    = ref('');
const senderName   = ref('Captain Rodel – Butal Ship Hauz');
const threadBody   = ref(null);

// Compose form
const composeForm = ref({
    client: 'Juan dela Cruz – BSH-2025-0042',
    type: 'message',
    subject: '',
    body: '',
    sender: 'Captain Rodel – Butal Ship Hauz',
});

// ── Computed ─────────────────────────────────────────────────────────────────
const threads = computed(() =>
    props.threads.length ? props.threads : mockThreads.value,
);

const unreadCount = computed(() => threads.value.filter((t) => !t.read).length);

const filtered = computed(() => {
    if (activeTab.value === 'bookings')
        return threads.value.filter((t) =>
            ['booking_reminder', 'booking_confirmed'].includes(t.type),
        );
    if (activeTab.value === 'messages')
        return threads.value.filter((t) => t.type === 'message');
    return threads.value;
});

// ── Helpers ──────────────────────────────────────────────────────────────────
function pillClass(type) {
    return (
        {
            booking_reminder: 'bg-yellow-100 text-yellow-800',
            booking_confirmed: 'bg-green-100 text-green-800',
            message: 'bg-blue-100 text-blue-800',
        }[type] ?? 'bg-gray-100 text-gray-700'
    );
}

function pillLabel(type) {
    return (
        { booking_reminder: 'Reminder', booking_confirmed: 'Confirmed', message: 'Message' }[type] ??
        'Notice'
    );
}

// ── Actions ──────────────────────────────────────────────────────────────────
function openThread(thread) {
    thread.read = true;
    activeThread.value = thread;
    isCompose.value = false;
    replyText.value = '';
    // TODO: axios.patch(route('admin.notifications.markRead', thread.id))
    scrollThread();
}

function scrollThread() {
    setTimeout(() => {
        if (threadBody.value) threadBody.value.scrollTop = threadBody.value.scrollHeight;
    }, 50);
}

function sendReply(thread) {
    if (!replyText.value.trim()) return;
    thread.messages.push({
        id: `${thread.id}-${Date.now()}`,
        from: 'admin',
        name: senderName.value.split(' – ')[0],
        body: replyText.value.trim(),
        time: 'Just now',
    });
    replyText.value = '';
    scrollThread();
    // TODO: axios.post(route('admin.notifications.reply', thread.id), { body, sender })
}

function deleteThread(thread) {
    if (!confirm('Delete this conversation? This cannot be undone.')) return;
    const idx = mockThreads.value.findIndex((t) => t.id === thread.id);
    if (idx > -1) mockThreads.value.splice(idx, 1);
    activeThread.value = null;
    // TODO: axios.delete(route('admin.notifications.destroy', thread.id))
}

function showCompose() {
    isCompose.value = true;
    activeThread.value = null;
    composeForm.value = { client: 'Juan dela Cruz – BSH-2025-0042', type: 'message', subject: '', body: '', sender: 'Captain Rodel – Butal Ship Hauz' };
}

function submitCompose() {
    if (!composeForm.value.body.trim()) return;
    const newThread = {
        id: Date.now(),
        type: composeForm.value.type,
        read: true,
        client: composeForm.value.client.split(' – ')[0],
        ref: composeForm.value.client.split(' – ')[1] ?? '',
        subject: composeForm.value.subject || 'New message',
        date: 'Today',
        time: 'Just now',
        messages: [
            {
                id: 'm1',
                from: 'admin',
                name: composeForm.value.sender.split(' – ')[0],
                body: composeForm.value.body.trim(),
                time: 'Just now',
            },
        ],
        canReply: true,
    };
    mockThreads.value.unshift(newThread);
    isCompose.value = false;
    activeThread.value = newThread;
    // TODO: axios.post(route('admin.notifications.store'), composeForm.value)
}
</script>

<template>
    <Head title="Messages" />

    <AdminLayout>
        <div>
            <div class="flex h-[640px] rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">

                <!-- ── Sidebar ─────────────────────────────────────────── -->
                <aside class="w-72 flex-shrink-0 border-r border-gray-200 flex flex-col">

                    <!-- Header -->
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                            </svg>
                            <span class="font-semibold text-slate-900 text-sm">Messages</span>
                            <span v-if="unreadCount" class="ml-auto rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold text-blue-900">
                                {{ unreadCount }}
                            </span>
                        </div>
                        <button
                            class="w-full flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-4 py-2 text-sm font-medium text-white hover:bg-blue-950 transition"
                            @click="showCompose"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Z" />
                            </svg>
                            New message
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-gray-100">
                        <button
                            v-for="tab in [{ key: 'all', label: 'All' }, { key: 'bookings', label: 'Bookings' }, { key: 'messages', label: 'Messages' }]"
                            :key="tab.key"
                            class="flex-1 py-2.5 text-xs font-medium border-b-2 transition"
                            :class="activeTab === tab.key
                                ? 'border-amber-400 text-slate-900'
                                : 'border-transparent text-gray-500 hover:text-slate-900'"
                            @click="activeTab = tab.key"
                        >
                            {{ tab.label }}
                        </button>
                    </div>

                    <!-- Thread list -->
                    <div class="flex-1 overflow-y-auto">
                        <div
                            v-for="thread in filtered"
                            :key="thread.id"
                            class="relative cursor-pointer border-b border-gray-100 px-4 py-3 transition hover:bg-gray-50"
                            :class="{
                                'bg-blue-50 hover:bg-blue-50': activeThread?.id === thread.id && !isCompose,
                                'border-l-2 border-l-amber-400': !thread.read,
                            }"
                            @click="openThread(thread)"
                        >
                            <span
                                v-if="!thread.read"
                                class="absolute top-3.5 right-3 h-2 w-2 rounded-full bg-amber-400"
                            />
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-xs font-medium text-slate-800" :class="{ 'font-semibold': !thread.read }">
                                    {{ thread.client }}
                                </span>
                                <span class="ml-auto pr-4 text-[10px] text-gray-400">{{ thread.date }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold" :class="pillClass(thread.type)">
                                    {{ pillLabel(thread.type) }}
                                </span>
                                <span class="font-mono text-[10px] text-gray-400 bg-gray-100 rounded px-1">{{ thread.ref }}</span>
                            </div>
                            <p class="truncate text-xs text-gray-500">{{ thread.subject }}</p>
                        </div>

                        <div v-if="filtered.length === 0" class="flex flex-col items-center gap-2 py-16 text-center text-gray-400">
                            <span class="text-3xl">📭</span>
                            <p class="text-sm">No threads here</p>
                        </div>
                    </div>
                </aside>

                <!-- ── Main panel ──────────────────────────────────────── -->
                <main class="flex flex-1 flex-col overflow-hidden">

                    <!-- Empty state -->
                    <div
                        v-if="!activeThread && !isCompose"
                        class="flex flex-1 flex-col items-center justify-center text-gray-400 gap-3"
                    >
                        <span class="text-5xl">💬</span>
                        <p class="font-medium text-gray-500">Select a conversation</p>
                        <p class="text-sm">or compose a new message</p>
                    </div>

                    <!-- Compose panel -->
                    <div v-else-if="isCompose" class="flex flex-1 flex-col overflow-y-auto p-6 gap-4">
                        <h3 class="text-base font-semibold text-slate-900">New message</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">To (client)</label>
                                <select v-model="composeForm.client" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-900 focus:outline-none">
                                    <option>Juan dela Cruz – BSH-2025-0042</option>
                                    <option>Maria Santos – BSH-2025-0038</option>
                                    <option>All clients with upcoming bookings</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500">Message type</label>
                                <select v-model="composeForm.type" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-900 focus:outline-none">
                                    <option value="message">Message</option>
                                    <option value="booking_reminder">Booking reminder</option>
                                    <option value="booking_confirmed">Booking confirmed</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Subject</label>
                            <input
                                v-model="composeForm.subject"
                                type="text"
                                placeholder="e.g. Your booking is confirmed"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-900 focus:outline-none"
                            />
                        </div>

                        <div class="flex-1">
                            <label class="mb-1 block text-xs text-gray-500">Message body</label>
                            <textarea
                                v-model="composeForm.body"
                                rows="7"
                                placeholder="Write your message here…"
                                class="w-full resize-none rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900/10"
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-xs text-gray-500">Send as</label>
                            <select v-model="composeForm.sender" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-900 focus:outline-none">
                                <option>Captain Rodel – Butal Ship Hauz</option>
                                <option>The Butal Ship Hauz Team</option>
                                <option>System</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button
                                :disabled="!composeForm.body.trim()"
                                class="flex items-center gap-2 rounded-lg bg-blue-900 px-5 py-2 text-sm font-medium text-white hover:bg-blue-950 transition disabled:cursor-not-allowed disabled:opacity-40"
                                @click="submitCompose"
                            >
                                Send message
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                            <button
                                class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 transition"
                                @click="isCompose = false"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Thread view -->
                    <template v-else-if="activeThread">
                        <!-- Thread header -->
                        <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">{{ activeThread.subject }}</h3>
                                <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                    <span>{{ activeThread.client }}</span>
                                    <span class="font-mono bg-gray-100 rounded px-1">{{ activeThread.ref }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold" :class="pillClass(activeThread.type)">
                                        {{ pillLabel(activeThread.type) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-500 hover:bg-gray-50 transition">
                                    Archive
                                </button>
                                <button
                                    class="flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs text-red-500 hover:bg-red-50 transition"
                                    @click="deleteThread(activeThread)"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div ref="threadBody" class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
                            <div
                                v-for="msg in activeThread.messages"
                                :key="msg.id"
                                class="flex"
                                :class="msg.from === 'client' ? 'justify-end' : 'justify-start'"
                            >
                                <div
                                    class="max-w-[72%] rounded-xl px-4 py-2.5 text-sm leading-relaxed"
                                    :class="msg.from === 'client'
                                        ? 'rounded-br-sm bg-blue-900 text-white'
                                        : 'rounded-bl-sm border border-gray-200 bg-gray-50 text-gray-800'"
                                >
                                    <p class="mb-0.5 text-[10px] font-bold uppercase tracking-wide opacity-60">{{ msg.name }}</p>
                                    <p>{{ msg.body }}</p>
                                    <p class="mt-1 text-right text-[10px] opacity-50">{{ msg.time }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reply box -->
                        <div v-if="activeThread.canReply" class="border-t border-gray-100 px-6 py-4">
                            <div class="mb-2 flex items-center gap-2">
                                <span class="text-xs text-gray-500">Reply as:</span>
                                <select v-model="senderName" class="rounded-md border border-gray-200 px-2 py-1 text-xs text-gray-700 focus:border-blue-900 focus:outline-none">
                                    <option>Captain Rodel – Butal Ship Hauz</option>
                                    <option>The Butal Ship Hauz Team</option>
                                </select>
                            </div>
                            <textarea
                                v-model="replyText"
                                rows="3"
                                placeholder="Type your reply here…"
                                class="w-full resize-none rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900/10"
                                @keydown.ctrl.enter="sendReply(activeThread)"
                            />
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">Ctrl + Enter to send</span>
                                <button
                                    :disabled="!replyText.trim()"
                                    class="flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-950 transition disabled:cursor-not-allowed disabled:opacity-40"
                                    @click="sendReply(activeThread)"
                                >
                                    Send ↗
                                </button>
                            </div>
                        </div>
                    </template>
                </main>
            </div>
        </div>
    </AdminLayout>
</template>