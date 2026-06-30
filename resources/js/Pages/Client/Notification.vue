<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted } from "vue";

const props = defineProps({
    notifications: {
        type: Array,
        default: () => [],
    },
    auth: {
        type: Object,
        default: () => ({}),
    },
});

const activeTab = ref("all");
const replyText = ref("");
const replyingTo = ref(null);
const expandedThread = ref(null);

const notifications = computed(() => props.notifications);

const filtered = computed(() => {
    if (activeTab.value === "booking")
        return notifications.value.filter((n) =>
            ["booking_reminder", "booking_confirmed"].includes(n.type),
        );
    if (activeTab.value === "messages")
        return notifications.value.filter((n) => n.type === "message");
    return notifications.value;
});

const unreadCount = computed(
    () => notifications.value.filter((n) => !n.read).length,
);

function formatDate(ts) {
    return new Date(ts).toLocaleDateString("en-PH", {
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function typeLabel(type) {
    return (
        {
            booking_reminder: "Reminder",
            booking_confirmed: "Confirmed",
            message: "Message",
        }[type] ?? "Notice"
    );
}

function iconBgClass(type) {
    return (
        {
            booking_reminder: "bg-yellow-100",
            booking_confirmed: "bg-green-100",
            message: "bg-blue-100",
        }[type] ?? "bg-gray-100"
    );
}

function badgeClass(type) {
    return (
        {
            booking_reminder: "bg-yellow-100 text-yellow-800",
            booking_confirmed: "bg-green-100 text-green-800",
            message: "bg-blue-100 text-blue-800",
        }[type] ?? "bg-gray-100 text-gray-700"
    );
}

function markRead(notif) {
    if (notif.read) {
        expandedThread.value =
            expandedThread.value === notif.id ? null : notif.id;
        return;
    }
    notif.read = true;
    expandedThread.value = expandedThread.value === notif.id ? null : notif.id;
    axios.patch(route("client.notifications.markRead", notif.id));
}

function toggleThread(notif) {
    markRead(notif);
}

function openReply(notif) {
    replyingTo.value = notif.id;
    replyText.value = "";
}

function sendReply(notif) {
    if (!replyText.value.trim()) return;

    router.post(
        route("client.notifications.reply", notif.id),
        {
            body: replyText.value.trim(),
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                replyText.value = "";
                replyingTo.value = null;
            },
        },
    );
}

function markAllRead() {
    router.patch(
        route("client.notifications.markAllRead"),
        {},
        { preserveScroll: true },
    );
}

let pollTimer = null;

onMounted(() => {
    pollTimer = setInterval(() => {
        router.reload({
            only: ["notifications"],
            preserveScroll: true,
            preserveState: true,
        });
    }, 5000);
});

onUnmounted(() => {
    if (pollTimer) clearInterval(pollTimer);
});
</script>

<template>
    <Head title="Notifications" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-100 pb-12">
            <!-- ── Sticky Header ── -->
            <div class="sticky top-0 z-20 bg-white rounded-xl shadow-lg">
                <div class="mx-auto px-4 pt-5 pb-0">
                    <!-- Title row -->
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-4 sm:gap-0 pb-4"
                    >
                        <div class="flex items-center gap-3">
                            <svg
                                class="w-10 h-10 fill-brass flex-shrink-0"
                                viewBox="0 0 48 48"
                            >
                                <path
                                    d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                                />
                            </svg>
                            <div>
                                <h1
                                    class="text-lg font-bold leading-tight text-slate-900"
                                >
                                    Notifications
                                </h1>
                                <p
                                    class="text-xs uppercase tracking-widest text-brass"
                                >
                                    The Butal Ship Hauz
                                </p>
                            </div>
                        </div>
                        <!-- Action buttons -->
                        <div class="flex items-center gap-2">
                            <button
                                v-if="unreadCount > 0"
                                class="rounded-full border border-gray-500 px-4 py-1.5 text-sm text-gray-500 transition hover:bg-slate-900 hover:text-white disabled:cursor-not-allowed disabled:opacity-30"
                                @click="markAllRead"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-check-double"
                                />
                                Mark all read
                            </button>
                        </div>
                    </div>
                    <hr class="my-1" />
                    <!-- Tabs -->
                    <div class="flex">
                        <button
                            v-for="tab in [
                                { key: 'all', label: 'All' },
                                { key: 'booking', label: 'Bookings' },
                                { key: 'messages', label: 'Messages' },
                            ]"
                            :key="tab.key"
                            class="flex flex-1 items-center justify-center gap-1.5 border-b-2 py-3 text-sm font-medium transition"
                            :class="
                                activeTab === tab.key
                                    ? 'border-amber-400 text-slate-900'
                                    : 'border-transparent text-gray-500 hover:text-slate-900'
                            "
                            @click="activeTab = tab.key"
                        >
                            {{ tab.label }}
                            <span
                                v-if="tab.key === 'all' && unreadCount"
                                class="rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold text-blue-900"
                            >
                                {{ unreadCount }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── Notification List ── -->
            <div class="mx-auto mt-5 space-y-3 px-1">
                <!-- Empty state -->
                <div
                    v-if="filtered.length === 0"
                    class="flex flex-col items-center gap-2 rounded-xl bg-white py-16 text-center text-gray-400 shadow-sm"
                >
                    <span class="text-4xl">🔔</span>
                    <p class="font-medium text-gray-500">
                        No notifications yet
                    </p>
                    <p class="text-sm">
                        We'll let you know when something comes in.
                    </p>
                </div>

                <!-- Card -->
                <div
                    v-for="notif in filtered"
                    :key="notif.id"
                    class="overflow-hidden rounded-xl bg-white shadow-sm transition hover:shadow-md"
                    :class="
                        !notif.read
                            ? 'border-l-4 border-amber-400 bg-amber-60/20'
                            : 'border-l-4 border-transparent'
                    "
                >
                    <!-- Top row (clickable) -->
                    <div
                        class="flex cursor-pointer items-start gap-3 p-4"
                        @click="markRead(notif)"
                    >
                        <!-- Icon -->
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-lg"
                            :class="iconBgClass(notif.type)"
                        >
                            <span v-if="notif.type === 'booking_reminder'"
                                >🕐</span
                            >
                            <span v-else-if="notif.type === 'booking_confirmed'"
                                >✅</span
                            >
                            <span v-else>💬</span>
                        </div>

                        <!-- Content -->
                        <div class="min-w-0 flex-1">
                            <!-- Meta row -->
                            <div
                                class="mb-1 flex flex-wrap items-center gap-1.5"
                            >
                                <span
                                    class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide"
                                    :class="badgeClass(notif.type)"
                                >
                                    {{ typeLabel(notif.type) }}
                                </span>
                                <span
                                    v-if="notif.booking_ref"
                                    class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[10px] text-gray-500"
                                >
                                    {{ notif.booking_ref }}
                                </span>
                                <span
                                    class="ml-auto hidden text-[11px] text-gray-500 sm:inline"
                                >
                                    {{ formatDate(notif.timestamp) }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-sm font-semibold text-blue-900">
                                {{ notif.title }}
                            </h3>

                            <!-- Body -->
                            <p
                                class="mt-0.5 text-sm leading-relaxed text-gray-600"
                            >
                                {{ notif.body }}
                            </p>

                            <!-- Sender -->
                            <p
                                v-if="notif.sender === 'admin'"
                                class="mt-1 text-xs italic text-gray-400"
                            >
                                — {{ notif.sender_name }}
                            </p>

                            <!-- Mobile time -->
                            <p class="mt-1 text-[11px] text-gray-400 sm:hidden">
                                {{ formatDate(notif.timestamp) }}
                            </p>
                        </div>

                        <!-- Unread dot -->
                        <span
                            v-if="!notif.read"
                            class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-amber-400"
                        />
                    </div>

                    <!-- Thread section -->
                    <div
                        v-if="
                            expandedThread === notif.id &&
                            (notif.thread.length || notif.canReply)
                        "
                        class="border-t border-gray-100 bg-gray-50 px-4 py-3"
                    >
                        <!-- Messages -->
                        <div class="space-y-2.5">
                            <div
                                v-for="msg in notif.thread"
                                :key="msg.id"
                                class="flex"
                                :class="
                                    msg.from === 'client'
                                        ? 'justify-end'
                                        : 'justify-start'
                                "
                            >
                                <div
                                    class="max-w-[78%] rounded-xl px-3 py-2 text-sm"
                                    :class="
                                        msg.from === 'client'
                                            ? 'rounded-br-sm bg-blue-900 text-white'
                                            : 'rounded-bl-sm border border-gray-200 bg-white text-gray-700'
                                    "
                                >
                                    <p
                                        class="mb-0.5 text-[10px] font-bold uppercase tracking-wide opacity-60"
                                    >
                                        {{ msg.name }}
                                    </p>
                                    <p class="leading-snug">{{ msg.body }}</p>
                                    <p
                                        class="mt-1 text-right text-[10px] opacity-50"
                                    >
                                        {{ formatDate(msg.timestamp) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Reply -->
                        <div v-if="notif.canReply" class="mt-3">
                            <!-- Trigger -->
                            <div
                                v-if="replyingTo !== notif.id"
                                class="flex justify-end"
                            >
                                <button
                                    class="rounded-full border border-blue-900 px-4 py-1.5 text-xs font-medium text-blue-900 transition hover:bg-blue-900 hover:text-white"
                                    @click.stop="openReply(notif)"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-reply"
                                    />
                                    Reply
                                </button>
                            </div>

                            <!-- Reply box -->
                            <div v-else class="space-y-2">
                                <textarea
                                    v-model="replyText"
                                    rows="3"
                                    placeholder="Type your reply here…"
                                    class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 outline-none transition focus:border-blue-900 focus:ring-2 focus:ring-blue-900/10"
                                    @keydown.ctrl.enter="sendReply(notif)"
                                />
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] text-gray-400"
                                        >Ctrl + Enter to send</span
                                    >
                                    <div class="flex gap-2">
                                        <button
                                            class="rounded-md border border-gray-300 px-3 py-1.5 text-xs text-gray-500 transition hover:border-gray-400"
                                            @click.stop="replyingTo = null"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            class="rounded-md bg-blue-900 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-950 disabled:cursor-not-allowed disabled:opacity-40"
                                            :disabled="!replyText.trim()"
                                            @click.stop="sendReply(notif)"
                                        >
                                            Send
                                            <font-awesome-icon
                                                icon="fa-solid fa-paper-plane"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expand toggle -->
                    <button
                        v-if="notif.thread.length || notif.canReply"
                        class="w-full border-t border-gray-100 bg-gray-50 py-2 text-xs font-medium text-blue-900 transition hover:bg-gray-100"
                        @click="toggleThread(notif)"
                    >
                        {{
                            expandedThread === notif.id
                                ? "▲ Hide thread"
                                : notif.thread.length
                                  ? `▼ View thread (${notif.thread.length})`
                                  : "▼ Reply"
                        }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
