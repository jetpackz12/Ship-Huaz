<script setup>
import { ref } from "vue";
const chatOpen = ref(false);

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

const chatMessages = ref([
    {
        from: "bot",
        text: "⚓ Ahoy! Welcome to Butal Ship Hauz. How can I help you today?",
    },
]);

const chatStep = ref("main");
const chatTyping = ref(false);

const chatMenus = {
    main: {
        label: "What can I help you with?",
        options: [
            { label: "🏛️ Venue Booking", next: "venue" },
            { label: "🛏️ Accommodations", next: "accommodations" },
            { label: "🌊 Island Tours", next: "tours" },
            { label: "🎉 Event Planning", next: "events" },
            { label: "🍽️ Dining & Catering", next: "dining" },
            { label: "📸 Photography Venue", next: "photo" },
            { label: "📍 Location & Hours", next: "location" },
            { label: "💬 Contact Us", next: "contact" },
        ],
    },
    venue: {
        label: "Venue Booking",
        reply: "Our iconic ship-shaped venue spaces start at ₱5,000 per event. We have 8 different event spaces for intimate dinners to grand receptions. Would you like to know more?",
        options: [
            { label: "💰 See pricing packages", next: "packages" },
            { label: "📅 How to book", next: "howtobook" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    accommodations: {
        label: "Accommodations",
        reply: "Spend the night aboard the Ship Hauz! Our nautical-themed rooms start at ₱1,200/night. Enjoy stunning views of the Bohol landscape right from your room.",
        options: [
            { label: "💰 See packages", next: "packages" },
            { label: "📅 How to book", next: "howtobook" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    tours: {
        label: "Island Tours",
        reply: "Explore Northern Bohol's natural wonders! We offer 12 island tour routes starting at ₱800/person. Hidden waterfalls, pristine beaches, and more await you.",
        options: [
            { label: "🗺️ See tour routes", next: "tourroutes" },
            { label: "💰 See packages", next: "packages" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    tourroutes: {
        label: "Tour Routes",
        reply: "We have 12 curated routes including island-hopping tours, waterfall treks, heritage site visits, and snorkeling adventures around Northern Bohol. All tours are guided and depart from Butal Ship Hauz.",
        options: [
            { label: "📅 How to book", next: "howtobook" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    events: {
        label: "Event Planning",
        reply: "Let our crew handle everything! Event planning packages start at ₱15,000 and cover weddings, debuts, corporate events, and reunions — coordination, catering, and venue all included.",
        options: [
            { label: "💒 Wedding packages", next: "wedding" },
            { label: "💰 All packages", next: "packages" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    wedding: {
        label: "Weddings at Ship Hauz",
        reply: "Say 'I do' at Bohol's most iconic venue! Our wedding packages include full catering, coordination, venue styling, and accommodation for the couple. Contact us for a custom quote tailored to your dream day.",
        options: [
            { label: "📞 Contact us", next: "contact" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    dining: {
        label: "Dining & Catering",
        reply: "Savor fresh Bohol flavors at our on-site restaurant! Dining starts at ₱350/person. We also offer custom catering services for groups of all sizes — barkada, family reunions, corporate events.",
        options: [
            { label: "🎉 Catering for events", next: "events" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    photo: {
        label: "Photography Venue",
        reply: "The Ship Hauz is Bohol's most photographed landmark! Photography sessions start at ₱2,500. Perfect for prenuptial shoots, fashion photography, debut pictorials, and commercial production.",
        options: [
            { label: "📅 How to book", next: "howtobook" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    location: {
        label: "Location & Hours",
        reply: "📍 Butal Ship Hauz, Talibon, Bohol, Philippines\n🕐 Open daily: 7:00 AM – 9:00 PM\n📞 Reservations: 8:00 AM – 6:00 PM\n📱 +63 (0) 912 345 6789",
        options: [
            { label: "🗺️ Get directions", next: "directions" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    directions: {
        label: "Directions",
        reply: "We're located in Talibon, the gateway to Northern Bohol's hidden gems. From Tagbilaran City, take a bus or van to Talibon (approx. 2–3 hours). Ask locals for 'Butal Ship Hauz' — everyone knows it! 😄",
        options: [
            { label: "📞 Call for pickup info", next: "contact" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    contact: {
        label: "Contact Us",
        reply: "Our crew is ready to help!\n📱 +63 (0) 912 345 6789\n✉️ reservations@butalshiphauz.com.ph\n\nFor reservations and inquiries, we respond within 24 hours.",
        options: [{ label: "⬅️ Back to menu", next: "main" }],
    },
    packages: {
        label: "Our Packages",
        reply: "🌅 Day Tour – ₱999 (venue access, 1 meal, guided tour)\n🌙 Overnight – ₱2,499 (room, 3 meals, island tour, venue access)\n🗓️ Weekend – ₱4,299 (2 nights, all meals, 2 tours, event hall)\n\nCustom packages also available — contact us!",
        options: [
            { label: "📞 Get a custom quote", next: "contact" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
    howtobook: {
        label: "How to Book",
        reply: "Booking is easy! 3 ways to reserve:\n1️⃣ Call us at +63 (0) 912 345 6789\n2️⃣ Email reservations@butalshiphauz.com.ph\n3️⃣ Use the 'Book a Reservation' button on this page\n\nA 50% down payment is required to confirm bookings.",
        options: [
            { label: "📞 Contact details", next: "contact" },
            { label: "⬅️ Back to menu", next: "main" },
        ],
    },
};

function handleOption(option) {
    const menu = chatMenus[chatStep.value];
    chatMessages.value.push({ from: "user", text: option.label });
    chatTyping.value = true;

    setTimeout(() => {
        chatTyping.value = false;
        const nextMenu = chatMenus[option.next];
        if (nextMenu) {
            if (nextMenu.reply) {
                chatMessages.value.push({ from: "bot", text: nextMenu.reply });
            }
            chatStep.value = option.next;
            if (nextMenu.options) {
                chatMessages.value.push({
                    from: "options",
                    label: nextMenu.label,
                    options: nextMenu.options,
                });
            }
        }
        scrollChat();
    }, 600);
}

function scrollChat() {
    setTimeout(() => {
        const el = document.getElementById("chat-body");
        if (el) el.scrollTop = el.scrollHeight;
    }, 50);
}

function openChat() {
    chatOpen.value = true;
    if (chatMessages.value.length === 1) {
        chatMessages.value.push({
            from: "options",
            label: chatMenus.main.label,
            options: chatMenus.main.options,
        });
    }
    scrollChat();
}
</script>

<template>
    <!-- ══════════ CHATBOT FAB ══════════ -->
    <div class="fixed bottom-6 right-6 z-50">
        <!-- Chat modal -->
        <Transition name="chat-pop">
            <div
                v-if="chatOpen"
                class="chat-modal mb-4 w-[340px] max-w-[calc(100vw-48px)] rounded-xl overflow-hidden shadow-2xl flex flex-col"
                style="
                    background: #0c1e35;
                    border: 1px solid rgba(196, 137, 58, 0.25);
                    max-height: 520px;
                "
            >
                <!-- Header -->
                <div
                    class="flex items-center justify-between px-4 py-3 border-b"
                    style="
                        border-color: rgba(196, 137, 58, 0.2);
                        background: #0e2540;
                    "
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-brass rounded-full flex items-center justify-center flex-shrink-0"
                        >
                            <svg class="w-4 h-4 fill-navy" viewBox="0 0 24 24">
                                <path
                                    d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <div
                                class="font-display text-white text-sm font-semibold leading-tight"
                            >
                                Ship Hauz Crew
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="w-1.5 h-1.5 bg-green-400 rounded-full"
                                ></span>
                                <span
                                    class="font-mono text-white/45 text-[10px] tracking-wider"
                                    >Online now</span
                                >
                            </div>
                        </div>
                    </div>
                    <button
                        @click="chatOpen = false"
                        class="w-7 h-7 rounded-full hover:bg-white/10 flex items-center justify-center transition-colors duration-150 text-white/50 hover:text-white"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Messages -->
                <div
                    id="chat-body"
                    class="flex-1 overflow-y-auto px-4 py-4 space-y-3"
                    style="min-height: 0"
                >
                    <template v-for="(msg, i) in chatMessages" :key="i">
                        <!-- Bot message -->
                        <div
                            v-if="msg.from === 'bot'"
                            class="flex items-start gap-2"
                        >
                            <div
                                class="w-6 h-6 bg-brass/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                            >
                                <svg
                                    class="w-3 h-3 fill-brass"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="chat-bubble-bot font-body text-white/90 text-[13px] leading-relaxed px-3 py-2 rounded-lg rounded-tl-none max-w-[85%]"
                                style="
                                    background: rgba(255, 255, 255, 0.08);
                                    white-space: pre-line;
                                "
                            >
                                {{ msg.text }}
                            </div>
                        </div>
                        <!-- User message -->
                        <div
                            v-else-if="msg.from === 'user'"
                            class="flex justify-end"
                        >
                            <div
                                class="font-body text-navy text-[13px] leading-relaxed px-3 py-2 rounded-lg rounded-tr-none max-w-[85%]"
                                style="background: #c4893a"
                            >
                                {{ msg.text }}
                            </div>
                        </div>
                        <!-- Options -->
                        <div v-else-if="msg.from === 'options'" class="pl-8">
                            <p
                                v-if="
                                    msg.label && i === chatMessages.length - 1
                                "
                                class="font-mono text-white/40 text-[10px] tracking-wider uppercase mb-2"
                            >
                                {{ msg.label }}
                            </p>
                            <div
                                v-if="i === chatMessages.length - 1"
                                class="flex flex-col gap-1.5"
                            >
                                <button
                                    v-for="opt in msg.options"
                                    :key="opt.label"
                                    @click="handleOption(opt)"
                                    class="chat-option-btn text-left font-body text-[12px] text-brass hover:text-navy hover:bg-brass px-3 py-2 rounded-md transition-all duration-150"
                                    style="
                                        border: 1px solid
                                            rgba(196, 137, 58, 0.35);
                                    "
                                >
                                    {{ opt.label }}
                                </button>
                            </div>
                        </div>
                    </template>
                    <!-- Typing indicator -->
                    <div v-if="chatTyping" class="flex items-start gap-2">
                        <div
                            class="w-6 h-6 bg-brass/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                        >
                            <svg class="w-3 h-3 fill-brass" viewBox="0 0 24 24">
                                <path
                                    d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"
                                />
                            </svg>
                        </div>
                        <div
                            class="px-3 py-3 rounded-lg rounded-tl-none"
                            style="background: rgba(255, 255, 255, 0.08)"
                        >
                            <div class="typing-dots flex gap-1">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer hint -->
                <div
                    class="px-4 py-2.5 border-t"
                    style="border-color: rgba(255, 255, 255, 0.08)"
                >
                    <p
                        class="font-mono text-white/25 text-[10px] tracking-wider text-center"
                    >
                        Select an option above to continue
                    </p>
                </div>
            </div>
        </Transition>

        <!-- FAB Button -->
        <button
            @click="chatOpen ? (chatOpen = false) : openChat()"
            class="chat-fab w-14 h-14 rounded-full shadow-xl flex items-center justify-center transition-all duration-300"
            :class="
                chatOpen
                    ? 'bg-black/50 border border-white/25'
                    : 'bg-brass hover:bg-gold'
            "
            aria-label="Open chat assistant"
        >
            <Transition name="icon-swap" mode="out-in">
                <svg
                    v-if="!chatOpen"
                    key="chat"
                    class="w-8 h-8 fill-navy"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M12 2a1 1 0 0 1 1 1v1h3a3 3 0 0 1 3 3v7a3 3 0 0 1-3 3h-1v2a1 1 0 1 1-2 0v-2h-2v2a1 1 0 1 1-2 0v-2H8a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h3V3a1 1 0 0 1 1-1zm4 5H8a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1zM10 10a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm-5 5h6a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2z"
                    />
                </svg>
                <svg
                    v-else
                    key="close"
                    class="w-5 h-5 fill-white"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
                    />
                </svg>
            </Transition>
        </button>

        <!-- Notification dot (shows when closed) -->
        <span
            v-if="!chatOpen"
            class="absolute top-0 right-0 w-3.5 h-3.5 bg-green-400 border-2 border-navy rounded-full"
        ></span>
    </div>
</template>
<style scoped>
/* ── Chat FAB animation ── */
.chat-pop-enter-active {
    transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.chat-pop-leave-active {
    transition: all 0.18s ease-in;
}
.chat-pop-enter-from {
    opacity: 0;
    transform: scale(0.85) translateY(12px);
    transform-origin: bottom right;
}
.chat-pop-leave-to {
    opacity: 0;
    transform: scale(0.9) translateY(8px);
    transform-origin: bottom right;
}

/* ── Icon swap ── */
.icon-swap-enter-active,
.icon-swap-leave-active {
    transition: all 0.15s ease;
}
.icon-swap-enter-from {
    opacity: 0;
    transform: scale(0.7) rotate(-20deg);
}
.icon-swap-leave-to {
    opacity: 0;
    transform: scale(0.7) rotate(20deg);
}

/* ── Typing indicator ── */
.typing-dots span {
    display: inline-block;
    width: 5px;
    height: 5px;
    background: rgba(196, 137, 58, 0.6);
    border-radius: 50%;
    animation: typingbounce 1.2s ease-in-out infinite;
}
.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}
.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}
@keyframes typingbounce {
    0%,
    60%,
    100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-5px);
    }
}

/* ── Chat FAB shadow ── */
.chat-fab {
    box-shadow: 0 8px 24px rgba(196, 137, 58, 0.35);
}
</style>
