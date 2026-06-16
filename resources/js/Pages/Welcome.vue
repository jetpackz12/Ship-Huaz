<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

const mobileMenuOpen = ref(false);
const chatOpen = ref(false);

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

const stats = ref([
    { num: "500+", label: "Happy Guests Monthly" },
    { num: "8", label: "Event Venue Spaces" },
    { num: "12", label: "Island Tour Routes" },
    { num: "#1", label: "Tourism Spot, Talibon" },
]);

const features = ref([
    {
        title: "Award-Winning Architecture",
        desc: "Cited as one of the most distinctive hospitality venues in the Philippines.",
        icon: '<path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.3L20 8.7v8.6L12 19.7 4 17.3V8.7L12 4.3z"/>',
    },
    {
        title: "Seasoned Hospitality Team",
        desc: "Our crew is dedicated to making every visit memorable and seamless.",
        icon: '<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>',
    },
    {
        title: "Prime Bohol Location",
        desc: "Conveniently located in Talibon gateway to Northern Bohol's hidden gems.",
        icon: '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>',
    },
]);

const services = ref([
    {
        tag: "Venue",
        title: "Venue Booking",
        price: "₱5,000",
        unit: "/ event",
        gradient: "linear-gradient(135deg,#133558,#1A6896)",
        desc: "Host your dream events inside the iconic Ship Hauz. From intimate dinners to grand receptions we have a space for every celebration.",
        icon: '<rect x="10" y="30" width="80" height="40" rx="2"/><rect x="30" y="10" width="40" height="22" rx="2"/><rect x="40" y="50" width="20" height="20"/>',
    },
    {
        tag: "Stay",
        title: "Accommodations",
        price: "₱1,200",
        unit: "/ night",
        gradient: "linear-gradient(135deg,#1f3f1c,#3a7a35)",
        desc: "Spend the night aboard the Ship Hauz. Cozy rooms with nautical-themed interiors and stunning views of the Bohol landscape.",
        icon: '<rect x="20" y="25" width="60" height="45" rx="3"/><path d="M15 25 L50 5 L85 25"/><rect x="38" y="50" width="24" height="20"/>',
    },
    {
        tag: "Tours",
        title: "Island Tours",
        price: "₱800",
        unit: "/ person",
        gradient: "linear-gradient(135deg,#4a2c0d,#b94b2c)",
        desc: "Explore the natural wonders of Northern Bohol with our curated island-hopping and adventure tour packages.",
        icon: '<circle cx="50" cy="40" r="25"/><path d="M30 60 Q50 75 70 60"/><path d="M25 40 L75 40"/><path d="M50 15 L50 65"/>',
    },
    {
        tag: "Events",
        title: "Event Planning",
        price: "₱15,000",
        unit: "/ package",
        gradient: "linear-gradient(135deg,#2c1a4a,#6b3aa3)",
        desc: "Leave it all to us. Our team will coordinate your wedding, debut, corporate event, or reunion from start to finish.",
        icon: '<rect x="15" y="20" width="70" height="50" rx="4"/><path d="M30 20 L30 10"/><path d="M70 20 L70 10"/><path d="M15 38 L85 38"/>',
    },
    {
        tag: "Dining",
        title: "Dining & Catering",
        price: "₱350",
        unit: "/ person",
        gradient: "linear-gradient(135deg,#4a350d,#c8973c)",
        desc: "Savor fresh Bohol flavors with our on-site restaurant and custom catering services for groups of all sizes.",
        icon: '<path d="M30 20 L30 60"/><path d="M70 20 L70 60"/><ellipse cx="50" cy="40" rx="30" ry="15"/>',
    },
    {
        tag: "Photo",
        title: "Photography Venue",
        price: "₱2,500",
        unit: "/ session",
        gradient: "linear-gradient(135deg,#0d3a4a,#1a8a9a)",
        desc: "Use the Ship Hauz as a dramatic backdrop for prenuptial shoots, fashion photography, or commercial production.",
        icon: '<rect x="15" y="25" width="70" height="45" rx="4"/><circle cx="50" cy="47" r="14"/><circle cx="50" cy="47" r="8"/><rect x="35" y="18" width="30" height="10" rx="3"/>',
    },
]);

const packages = ref([
    {
        name: "Day Tour",
        price: "₱999",
        desc: "Venue access, 1 meal, and a guided tour",
        featured: false,
    },
    {
        name: "⭐ Overnight",
        price: "₱2,499",
        desc: "Room, 3 meals, island tour, and venue access",
        featured: true,
    },
    {
        name: "Weekend",
        price: "₱4,299",
        desc: "2 nights, all meals, 2 tours, and event hall",
        featured: false,
    },
]);

const posts = ref([
    {
        date: "June 2025",
        category: "Destination",
        title: "Why Butal Ship Hauz is Bohol's Most Photographed Landmark",
        excerpt:
            "Thousands of tourists flock to Talibon each year specifically to see our ship-shaped masterpiece up close.",
        gradient: "linear-gradient(135deg,#0e2d4d,#1a6898)",
        icon: '<path d="M10 50 L30 30 L50 40 L70 20 L90 35 L90 70 L10 70Z"/>',
    },
    {
        date: "May 2025",
        category: "Events",
        title: "The Perfect Venue for Your Dream Bohol Wedding",
        excerpt:
            'Say "I do" aboard the most iconic venue in Northern Bohol. Our wedding packages include full catering and coordination.',
        gradient: "linear-gradient(135deg,#1a3a1c,#3a7a35)",
        icon: '<circle cx="50" cy="35" r="20"/><path d="M10 70 Q50 45 90 70"/>',
    },
    {
        date: "April 2025",
        category: "Travel Tips",
        title: "Top 5 Things to Do Near Butal Ship Hauz in Talibon",
        excerpt:
            "From hidden waterfalls to pristine beaches — the best activities to pair with your Ship Hauz stay.",
        gradient: "linear-gradient(135deg,#3a200d,#b94b2c)",
        icon: '<path d="M20 60 L50 10 L80 60 Z"/><rect x="35" y="60" width="30" height="15"/>',
    },
]);

const contactInfo = ref([
    {
        label: "Location",
        value: "Butal Ship Hauz, Talibon, Bohol<br/>Philippines",
        icon: '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>',
    },
    {
        label: "Phone",
        value: "+63 (0) 912 345 6789",
        icon: '<path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>',
    },
    {
        label: "Email",
        value: "reservations@butalshiphauz.com.ph",
        icon: '<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>',
    },
    {
        label: "Hours",
        value: "Daily, 7:00 AM – 9:00 PM<br/>Reservations: 8:00 AM – 6:00 PM",
        icon: '<path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/>',
    },
]);

const footerCols = ref([
    {
        heading: "Navigation",
        links: [
            { label: "Home", href: "#home" },
            { label: "About Us", href: "#about" },
            { label: "Services", href: "#services" },
            { label: "Blog & News", href: "#blog" },
            { label: "Contact", href: "#contact" },
        ],
    },
    {
        heading: "Services",
        links: [
            { label: "Venue Booking", href: "#services" },
            { label: "Accommodations", href: "#services" },
            { label: "Island Tours", href: "#services" },
            { label: "Event Planning", href: "#services" },
            { label: "Dining & Catering", href: "#services" },
        ],
    },
    {
        heading: "Legal",
        links: [
            { label: "Privacy Policy", href: "#" },
            { label: "Terms of Service", href: "#" },
            { label: "Booking Policy", href: "#" },
            { label: "Refund Policy", href: "#" },
        ],
    },
]);

const socials = ref([
    {
        label: "Facebook",
        icon: '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
    },
    {
        label: "Instagram",
        icon: '<rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1"/>',
    },
    {
        label: "TikTok",
        icon: '<path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.79 1.53V6.77a4.85 4.85 0 0 1-1.02-.08z"/>',
    },
]);

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
});
</script>

<template>
    <Head title="Butal Ship Hauz — Talibon, Bohol" />

    <div class="bg-navy text-white antialiased overflow-x-hidden font-body">
        <!-- ══════════ NAV ══════════ -->
        <nav
            class="fixed top-0 left-0 right-0 z-50 bg-navy/95 backdrop-blur-md border-b border-brass/20"
        >
            <div
                class="flex items-center justify-between px-6 md:px-10 h-[68px]"
            >
                <!-- Logo -->
                <a
                    href="#home"
                    class="flex items-center gap-3 no-underline flex-shrink-0"
                >
                    <svg
                        class="w-8 h-8 fill-brass flex-shrink-0"
                        viewBox="0 0 48 48"
                    >
                        <path
                            d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                        />
                    </svg>
                    <div>
                        <div
                            class="font-display text-white text-base font-semibold leading-tight"
                        >
                            Butal Ship Hauz
                        </div>
                        <div
                            class="font-mono text-brass text-[10px] tracking-[.18em] uppercase leading-none"
                        >
                            Talibon, Bohol
                        </div>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <ul class="hidden md:flex items-center gap-8 list-none m-0 p-0">
                    <li>
                        <a
                            href="#home"
                            class="nav-link font-mono text-[11px] tracking-[.14em] uppercase text-white/65 hover:text-white transition-colors duration-200"
                        >
                            <font-awesome-icon icon="fa-solid fa-house" />
                            Home
                        </a>
                    </li>
                    <li>
                        <a
                            href="#about"
                            class="nav-link font-mono text-[11px] tracking-[.14em] uppercase text-white/65 hover:text-white transition-colors duration-200"
                        >
                            <font-awesome-icon icon="fa-solid fa-circle-info" />
                            About
                        </a>
                    </li>
                    <li>
                        <a
                            href="#services"
                            class="nav-link font-mono text-[11px] tracking-[.14em] uppercase text-white/65 hover:text-white transition-colors duration-200"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-person-military-pointing"
                            />
                            Services
                        </a>
                    </li>
                    <li>
                        <a
                            href="#blog"
                            class="nav-link font-mono text-[11px] tracking-[.14em] uppercase text-white/65 hover:text-white transition-colors duration-200"
                        >
                            <font-awesome-icon icon="fa-solid fa-newspaper" />
                            News
                        </a>
                    </li>
                    <li>
                        <a
                            href="#contact"
                            class="nav-link font-mono text-[11px] tracking-[.14em] uppercase text-white/65 hover:text-white transition-colors duration-200"
                        >
                            <font-awesome-icon icon="fa-solid fa-id-card" />
                            Contact
                        </a>
                    </li>
                    <template v-if="canLogin">
                        <li v-if="$page.props.auth.user">
                            <Link
                                :href="route('home')"
                                class="font-mono text-[11px] tracking-[.14em] uppercase text-white/65 hover:text-white transition-colors duration-200"
                                >Dashboard</Link
                            >
                        </li>
                        <template v-else>
                            <li>
                                <Link
                                    :href="route('login')"
                                    class="font-mono text-[11px] tracking-[.14em] uppercase text-white/65 hover:text-white transition-colors duration-200"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-right-to-bracket"
                                    />
                                    Log in
                                </Link>
                            </li>
                            <li v-if="canRegister">
                                <Link
                                    :href="route('register')"
                                    class="font-body text-[13px] font-medium text-navy bg-brass hover:bg-gold px-5 py-2 rounded-sm transition-colors duration-200"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-circle-user"
                                    />
                                    CREATE ACCOUNT
                                </Link>
                            </li>
                        </template>
                    </template>
                </ul>

                <!-- Mobile: auth CTA + hamburger -->
                <div class="flex md:hidden items-center gap-3">
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="w-10 h-10 flex flex-col items-center justify-center gap-[5px] rounded-sm border border-white/15 hover:border-brass/40 transition-colors duration-200"
                        :aria-expanded="mobileMenuOpen"
                        aria-label="Toggle menu"
                    >
                        <span
                            class="hamburger-line"
                            :class="{
                                'rotate-45 translate-y-[7px]': mobileMenuOpen,
                            }"
                        ></span>
                        <span
                            class="hamburger-line"
                            :class="{ 'opacity-0 scale-x-0': mobileMenuOpen }"
                        ></span>
                        <span
                            class="hamburger-line"
                            :class="{
                                '-rotate-45 -translate-y-[7px]': mobileMenuOpen,
                            }"
                        ></span>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Drawer -->
            <div
                class="mobile-drawer md:hidden overflow-hidden transition-all duration-300 ease-in-out"
                :class="
                    mobileMenuOpen
                        ? 'mobile-drawer-open'
                        : 'mobile-drawer-closed'
                "
            >
                <div class="px-6 pb-6 pt-2 border-t border-white/10">
                    <!-- Nav links -->
                    <nav class="grid grid-cols-2 gap-1 mb-5">
                        <a
                            v-for="item in [
                                { label: 'Home', icon: 'fa-solid fa-house', href: '#home' },
                                { label: 'About', icon: 'fa-solid fa-circle-info', href: '#about' },
                                { label: 'Services', icon: 'fa-solid fa-person-military-pointing', href: '#services' },
                                { label: 'Blog & News', icon: 'fa-solid fa-newspaper', href: '#blog' },
                                { label: 'Contact', icon: 'fa-solid fa-id-card', href: '#contact' },
                            ]"
                            :key="item.label"
                            :href="item.href"
                            @click="mobileMenuOpen = false"
                            class="mobile-nav-item font-mono text-[11px] tracking-[.12em] uppercase text-white/70 hover:text-white hover:bg-white/8 px-4 py-3 rounded-sm transition-all duration-150 flex items-center gap-2"
                        >
                            <font-awesome-icon :icon="item.icon" />
                            {{ item.label }}
                        </a>
                    </nav>

                    <!-- Divider -->
                    <div class="h-px bg-white/10 mb-5"></div>

                    <!-- Auth section -->
                    <template v-if="canLogin">
                        <div
                            v-if="$page.props.auth.user"
                            class="flex justify-center"
                        >
                            <Link
                                :href="route('home')"
                                class="w-full text-center font-body text-sm font-medium text-white/70 border border-white/20 hover:border-brass/50 hover:text-white px-6 py-3 rounded-sm transition-colors duration-200"
                            >
                                Dashboard
                            </Link>
                        </div>
                        <div v-else class="flex flex-col gap-2">
                            <Link
                                :href="route('login')"
                                class="w-full text-center font-body text-sm font-medium text-white/70 border border-white/20 hover:border-white/40 hover:text-white px-6 py-3 rounded-sm transition-colors duration-200"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-right-to-bracket"
                                />
                                Log In
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="w-full text-center font-body text-sm font-semibold text-navy bg-brass hover:bg-gold px-6 py-3 rounded-sm transition-colors duration-200"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-circle-user"
                                />
                                Create Account
                            </Link>
                        </div>
                    </template>

                    <!-- Contact quick info -->
                    <div
                        class="mt-5 pt-5 border-t border-white/10 flex flex-col gap-2"
                    >
                        <a
                            href="tel:+639123456789"
                            class="flex items-center gap-2 text-white/40 hover:text-brass transition-colors duration-200"
                        >
                            <svg
                                class="w-3.5 h-3.5 fill-current flex-shrink-0"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"
                                />
                            </svg>
                            <span class="font-mono text-[10px] tracking-wider"
                                >+63 (0) 912 345 6789</span
                            >
                        </a>
                        <span class="flex items-center gap-2 text-white/40">
                            <svg
                                class="w-3.5 h-3.5 fill-current flex-shrink-0"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                                />
                            </svg>
                            <span class="font-mono text-[10px] tracking-wider"
                                >Talibon, Bohol, Philippines</span
                            >
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- ══════════ HERO ══════════ -->
        <section
            id="home"
            class="relative min-h-screen flex items-center justify-center overflow-hidden"
            style="
                background: linear-gradient(
                    180deg,
                    #060f1d 0%,
                    #0c1e35 40%,
                    #0e2845 65%,
                    #0a2138 100%
                );
            "
        >
            <div class="stars absolute inset-0 pointer-events-none"></div>
            <svg
                class="ship-float absolute bottom-[100px] left-1/2 w-[680px] max-w-[88vw] opacity-[.09] pointer-events-none"
                style="transform: translateX(-50%)"
                viewBox="0 0 700 280"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    fill="white"
                    d="M50 200 L80 140 L120 100 L140 60 L180 40 L220 60 L240 100 L280 80 L320 60 L360 80 L380 100 L400 80 L440 60 L480 80 L500 100 L540 120 L600 140 L640 180 L650 200 Z"
                />
                <path fill="white" d="M60 200 L640 200 L620 240 L80 240 Z" />
                <rect fill="white" x="160" y="90" width="10" height="110" />
                <rect fill="white" x="350" y="70" width="10" height="130" />
                <path fill="white" d="M170 90 L295 102 L170 114 Z" />
                <path fill="white" d="M360 70 L475 86 L360 102 Z" />
            </svg>
            <div class="wave-layer">
                <div class="wave-shape"></div>
                <div class="wave-shape"></div>
                <div class="wave-shape"></div>
            </div>
            <div class="relative z-10 text-center px-6 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 mb-7">
                    <div class="h-px w-8 bg-brass/60"></div>
                    <span
                        class="font-mono text-[11px] tracking-[.22em] uppercase text-brass"
                        >⚓ Talibon, Bohol, Philippines</span
                    >
                    <div class="h-px w-8 bg-brass/60"></div>
                </div>
                <h1
                    class="font-display text-white font-bold leading-[1.06] mb-6 hero-title"
                >
                    Where the Sea<br />
                    <em class="text-brass" style="font-style: italic"
                        >Comes Ashore</em
                    >
                </h1>
                <p
                    class="font-body text-white/55 text-lg font-light leading-relaxed mb-10 max-w-xl mx-auto"
                >
                    Bohol's most distinctive ship-shaped landmark offering venue
                    bookings, accommodations, and island tours in the heart of
                    Talibon.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <Link
                        href="/login"
                        class="inline-block bg-brass hover:bg-gold text-navy font-body font-medium text-sm tracking-wide px-8 py-4 rounded-sm transition-all duration-200 hover:-translate-y-px"
                        >Book a Reservation</Link
                    >
                    <a
                        href="#services"
                        class="inline-block border border-white/25 hover:border-brass text-white hover:text-brass font-body font-light text-sm tracking-wide px-8 py-4 rounded-sm transition-all duration-200"
                        >Explore Services</a
                    >
                </div>
            </div>
            <div
                class="pulse-scroll absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2"
            >
                <div
                    class="w-px h-10"
                    style="
                        background: linear-gradient(
                            to bottom,
                            rgba(255, 255, 255, 0.3),
                            transparent
                        );
                    "
                ></div>
                <span
                    class="font-mono text-[10px] tracking-[.18em] uppercase text-white/30"
                    >Scroll</span
                >
            </div>
        </section>

        <!-- ══════════ STATS ══════════ -->
        <div class="bg-navy border-y border-brass/15 py-14">
            <div
                class="max-w-5xl mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-10 text-center"
            >
                <div v-for="stat in stats" :key="stat.label">
                    <div
                        class="font-display text-brass font-bold leading-none mb-2 stat-num"
                    >
                        {{ stat.num }}
                    </div>
                    <div
                        class="font-mono text-white/45 text-[10px] tracking-[.16em] uppercase"
                    >
                        {{ stat.label }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════ ABOUT ══════════ -->
        <section id="about" class="py-28 bg-sand">
            <div class="max-w-6xl mx-auto px-8">
                <div class="grid md:grid-cols-2 gap-20 items-center">
                    <div class="relative">
                        <div
                            class="aspect-[4/3] rounded-sm overflow-hidden relative"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #133558 0%,
                                    #1a6896 55%,
                                    #2a8dc4 100%
                                );
                            "
                        >
                            <svg
                                class="absolute inset-0 w-full h-full opacity-20"
                                viewBox="0 0 400 300"
                                xmlns="http://www.w3.org/2000/svg"
                                preserveAspectRatio="xMidYMid slice"
                            >
                                <path
                                    fill="white"
                                    d="M40 200 L80 140 L120 100 L150 60 L200 40 L250 60 L280 100 L320 140 L360 200Z"
                                />
                                <path
                                    fill="white"
                                    d="M30 200 L370 200 L350 240 L50 240Z"
                                />
                                <rect
                                    fill="white"
                                    x="148"
                                    y="95"
                                    width="7"
                                    height="105"
                                />
                                <path
                                    fill="white"
                                    d="M155 95 L260 110 L155 125Z"
                                />
                            </svg>
                            <div
                                class="absolute bottom-0 left-0 right-0 h-1/3"
                                style="
                                    background: linear-gradient(
                                        180deg,
                                        transparent,
                                        rgba(12, 30, 53, 0.6)
                                    );
                                "
                            ></div>
                        </div>
                        <div
                            class="absolute -bottom-5 -right-5 w-28 h-28 bg-brass rounded-full flex flex-col items-center justify-center text-center shadow-xl"
                        >
                            <span
                                class="font-display text-navy font-bold leading-none"
                                style="font-size: 1.9rem"
                                >10+</span
                            >
                            <span
                                class="font-mono text-navy text-[9px] tracking-[.1em] uppercase mt-1 leading-snug"
                                >Years<br />of Service</span
                            >
                        </div>
                    </div>
                    <div>
                        <p
                            class="font-mono text-brass text-[11px] tracking-[.2em] uppercase mb-3"
                        >
                            About Butal Ship Hauz
                        </p>
                        <h2
                            class="font-display text-navy font-bold leading-tight mb-5 section-title"
                        >
                            Bohol's Iconic<br />Ship-Shaped Landmark
                        </h2>
                        <p
                            class="font-body text-mist text-base leading-relaxed mb-4"
                        >
                            Nestled in the heart of Talibon, Bohol, Butal Ship
                            Hauz is a beloved landmark that has captured the
                            imagination of thousands of visitors. Its remarkable
                            ship-shaped architecture is unlike anything else in
                            the Philippines.
                        </p>
                        <p
                            class="font-body text-mist text-base leading-relaxed"
                        >
                            Beyond its stunning exterior, Butal Ship Hauz offers
                            a full range of hospitality services from intimate
                            gatherings and grand celebrations to comfortable
                            accommodations and unforgettable island adventures.
                        </p>
                        <div class="mt-10 space-y-6">
                            <div
                                v-for="feat in features"
                                :key="feat.title"
                                class="flex gap-4 items-start"
                            >
                                <div
                                    class="flex-shrink-0 w-9 h-9 bg-navy rounded-sm flex items-center justify-center"
                                >
                                    <svg
                                        class="w-4 fill-brass"
                                        viewBox="0 0 24 24"
                                        v-html="feat.icon"
                                    ></svg>
                                </div>
                                <div>
                                    <strong
                                        class="block font-body font-semibold text-navy text-sm mb-1"
                                        >{{ feat.title }}</strong
                                    >
                                    <p
                                        class="font-body text-mist text-sm leading-relaxed"
                                    >
                                        {{ feat.desc }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════ SERVICES ══════════ -->
        <section id="services" class="py-28 bg-white">
            <div class="max-w-6xl mx-auto px-8">
                <div class="mb-14">
                    <p
                        class="font-mono text-brass text-[11px] tracking-[.2em] uppercase mb-3"
                    >
                        Services & Packages
                    </p>
                    <h2
                        class="font-display text-navy font-bold leading-tight section-title"
                    >
                        Everything You Need<br />Under One Deck
                    </h2>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    <div
                        v-for="svc in services"
                        :key="svc.title"
                        class="card-hover border border-slate-100 rounded-sm overflow-hidden bg-white"
                    >
                        <div
                            class="h-48 relative flex items-center justify-center overflow-hidden"
                            :style="{ background: svc.gradient }"
                        >
                            <svg
                                class="w-1/2 opacity-25 fill-white"
                                viewBox="0 0 100 80"
                                v-html="svc.icon"
                            ></svg>
                            <span
                                class="absolute top-4 left-4 font-mono text-[10px] tracking-[.14em] uppercase bg-black/40 text-brass px-2.5 py-1 rounded-sm"
                                >{{ svc.tag }}</span
                            >
                        </div>
                        <div class="p-6">
                            <h3
                                class="font-display text-navy font-bold text-xl mb-2"
                            >
                                {{ svc.title }}
                            </h3>
                            <p
                                class="font-body text-mist text-sm leading-relaxed"
                            >
                                {{ svc.desc }}
                            </p>
                            <div
                                class="flex items-baseline gap-2 mt-5 pt-5 border-t border-slate-100"
                            >
                                <span
                                    class="font-mono text-mist text-[10px] tracking-widest uppercase"
                                    >From</span
                                >
                                <span
                                    class="font-display text-brass font-bold text-2xl"
                                    >{{ svc.price }}</span
                                >
                                <span class="font-body text-mist text-xs">{{
                                    svc.unit
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-20 bg-navy rounded-sm overflow-hidden">
                    <div class="grid md:grid-cols-5">
                        <div
                            class="md:col-span-2 p-12 flex flex-col justify-center border-r border-white/10"
                        >
                            <p
                                class="font-mono text-brass text-[11px] tracking-[.2em] uppercase mb-3"
                            >
                                Bundle & Save
                            </p>
                            <h2
                                class="font-display text-white font-bold leading-tight text-3xl mb-4"
                            >
                                All-in-One Packages
                            </h2>
                            <p
                                class="font-body text-white/50 text-sm leading-relaxed mb-8"
                            >
                                Combine accommodations, dining, and tours for
                                the best value. Perfect for family getaways,
                                barkada trips, and corporate retreats.
                            </p>
                            <a
                                href="#contact"
                                class="self-start inline-block bg-brass hover:bg-gold text-navy font-body font-medium text-sm tracking-wide px-6 py-3 rounded-sm transition-colors duration-200"
                                >Get a Custom Quote</a
                            >
                        </div>
                        <div
                            class="md:col-span-3 grid md:grid-cols-3 divide-x divide-white/10"
                        >
                            <div
                                v-for="pkg in packages"
                                :key="pkg.name"
                                class="p-8 flex flex-col justify-center text-center relative transition-colors duration-200"
                                :class="
                                    pkg.featured
                                        ? 'pkg-featured'
                                        : 'hover:bg-white/5'
                                "
                            >
                                <div
                                    v-if="pkg.featured"
                                    class="absolute top-0 left-0 right-0 h-0.5 bg-brass"
                                ></div>
                                <div
                                    class="font-mono text-brass text-[10px] tracking-[.18em] uppercase mb-4"
                                >
                                    {{ pkg.name }}
                                </div>
                                <div
                                    class="font-display font-bold leading-none mb-3 pkg-price"
                                    :class="
                                        pkg.featured
                                            ? 'text-brass'
                                            : 'text-white'
                                    "
                                    style="font-size: 2.2rem"
                                >
                                    {{ pkg.price }}
                                </div>
                                <p
                                    class="font-body text-white/40 text-xs leading-relaxed"
                                >
                                    {{ pkg.desc }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════ BLOG ══════════ -->
        <section id="blog" class="py-28 bg-foam">
            <div class="max-w-6xl mx-auto px-8">
                <p
                    class="font-mono text-brass text-[11px] tracking-[.2em] uppercase mb-3"
                >
                    Blog & News
                </p>
                <h2
                    class="font-display text-navy font-bold leading-tight mb-14 section-title"
                >
                    Stories from the Ship
                </h2>
                <div class="grid md:grid-cols-3 gap-7">
                    <article
                        v-for="post in posts"
                        :key="post.title"
                        class="card-hover bg-white rounded-sm overflow-hidden"
                    >
                        <div
                            class="h-44 relative flex items-center justify-center"
                            :style="{ background: post.gradient }"
                        >
                            <svg
                                class="w-2/5 opacity-25 fill-white"
                                viewBox="0 0 100 80"
                                v-html="post.icon"
                            ></svg>
                            <span
                                class="absolute top-4 left-4 font-mono text-navy bg-white/90 text-[10px] tracking-[.1em] px-2.5 py-1 rounded-sm uppercase"
                                >{{ post.date }}</span
                            >
                        </div>
                        <div class="p-6">
                            <div
                                class="font-mono text-brass text-[10px] tracking-[.16em] uppercase mb-2"
                            >
                                {{ post.category }}
                            </div>
                            <h3
                                class="font-display text-navy font-bold text-lg leading-snug mb-3"
                            >
                                {{ post.title }}
                            </h3>
                            <p
                                class="font-body text-mist text-sm leading-relaxed"
                            >
                                {{ post.excerpt }}
                            </p>
                            <a
                                href="#"
                                class="inline-flex items-center gap-1.5 mt-5 font-mono text-sea hover:text-navy text-[10px] tracking-[.1em] uppercase transition-colors duration-200"
                                >Read Article →</a
                            >
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- ══════════ CONTACT ══════════ -->
        <section id="contact" class="py-28 bg-navy">
            <div class="max-w-6xl mx-auto px-8">
                <div class="grid md:grid-cols-2 gap-20 items-start">
                    <div>
                        <p
                            class="font-mono text-brass text-[11px] tracking-[.2em] uppercase mb-3"
                        >
                            Get in Touch
                        </p>
                        <h2
                            class="font-display text-white font-bold leading-tight mb-5 section-title"
                        >
                            Set Sail With Us
                        </h2>
                        <p
                            class="font-body text-white/50 text-base leading-relaxed"
                        >
                            Ready to book your visit or inquire about our
                            packages? Reach out and our crew will get back to
                            you within 24 hours.
                        </p>
                        <div class="mt-12 space-y-8">
                            <div
                                v-for="info in contactInfo"
                                :key="info.label"
                                class="flex gap-4 items-start"
                            >
                                <div
                                    class="flex-shrink-0 w-10 h-10 border border-brass/30 bg-brass/10 rounded-sm flex items-center justify-center"
                                >
                                    <svg
                                        class="w-4 fill-brass"
                                        viewBox="0 0 24 24"
                                        v-html="info.icon"
                                    ></svg>
                                </div>
                                <div>
                                    <div
                                        class="font-mono text-brass text-[10px] tracking-[.14em] uppercase mb-1"
                                    >
                                        {{ info.label }}
                                    </div>
                                    <div
                                        class="font-body text-white/75 text-sm leading-relaxed"
                                        v-html="info.value"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="border border-white/10 rounded-sm overflow-hidden"
                        style="background: rgba(255, 255, 255, 0.05)"
                    >
                        <div class="p-6 border-b border-white/10">
                            <h3
                                class="font-display text-white font-bold text-2xl"
                            >
                                Find Us on the Map
                            </h3>
                            <p class="text-white/50 text-sm mt-2">
                                Butal Ship Hauz, Talibon, Bohol
                            </p>
                        </div>
                        <div class="w-full h-[420px]">
                            <iframe
                                class="w-full h-full"
                                frameborder="0"
                                style="border: 0"
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps?q=10.150360879723529,124.32322880265386&z=17&output=embed"
                                allowfullscreen
                            ></iframe>
                        </div>
                        <div class="p-6">
                            <a
                                href="https://www.google.com/maps?q=10.150360879723529,124.32322880265386"
                                target="_blank"
                                class="block text-center w-full bg-brass hover:bg-gold text-navy font-semibold text-sm tracking-wide py-4 rounded-sm transition-colors duration-200"
                            >
                                📍 Open in Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════ FOOTER ══════════ -->
        <footer
            class="border-t border-brass/15 pt-16 pb-8"
            style="background: #060f1c"
        >
            <div class="max-w-6xl mx-auto px-8">
                <div class="grid md:grid-cols-4 gap-12 mb-14">
                    <div class="md:col-span-1">
                        <a href="#home" class="flex items-center justify-center gap-3 mb-5">
                            <svg
                                class="w-7 h-7 fill-brass flex-shrink-0"
                                viewBox="0 0 48 48"
                            >
                                <path
                                    d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                                />
                            </svg>
                            <div>
                                <div
                                    class="font-display text-white text-sm font-semibold leading-tight"
                                >
                                    Butal Ship Hauz
                                </div>
                                <div
                                    class="font-mono text-brass text-[9px] tracking-[.16em] uppercase"
                                >
                                    Talibon, Bohol
                                </div>
                            </div>
                        </a>
                        <p
                            class="font-body text-white/35 text-center text-xs leading-relaxed"
                        >
                            Bohol's most iconic ship-themed hospitality landmark
                            where every visit feels like an adventure at sea.
                        </p>
                    </div>
                    <div v-for="col in footerCols" :key="col.heading">
                        <h4
                            class="font-mono text-brass text-center text-[10px] tracking-[.2em] uppercase mb-5"
                        >
                            {{ col.heading }}
                        </h4>
                        <ul class="space-y-3 list-none p-0 flex flex-col items-center justify-center">
                            <li v-for="link in col.links" :key="link.label">
                                <a
                                    :href="link.href"
                                    class="font-body text-white/40 hover:text-white/80 text-sm transition-colors duration-200"
                                    >{{ link.label }}</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
                <div
                    class="h-px mb-7"
                    style="
                        background: linear-gradient(
                            90deg,
                            transparent,
                            rgba(196, 137, 58, 0.4),
                            transparent
                        );
                    "
                ></div>
                <div
                    class="flex flex-col md:flex-row justify-between items-center gap-4"
                >
                    <p
                        class="font-mono text-white/25 text-[10px] tracking-[.08em]"
                    >
                        © 2025 Butal Ship Hauz. All rights reserved. Talibon,
                        Bohol, Philippines.
                    </p>
                    <div class="flex gap-3">
                        <a
                            v-for="social in socials"
                            :key="social.label"
                            href="#"
                            :aria-label="social.label"
                            class="w-8 h-8 border border-white/15 hover:border-brass/50 rounded-full flex items-center justify-center transition-colors duration-200"
                        >
                            <svg
                                class="w-3.5 fill-white/50"
                                viewBox="0 0 24 24"
                                v-html="social.icon"
                            ></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

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
                                <svg
                                    class="w-4 h-4 fill-navy"
                                    viewBox="0 0 24 24"
                                >
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
                            <svg
                                class="w-4 h-4 fill-current"
                                viewBox="0 0 24 24"
                            >
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
                            <div
                                v-else-if="msg.from === 'options'"
                                class="pl-8"
                            >
                                <p
                                    v-if="
                                        msg.label &&
                                        i === chatMessages.length - 1
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
                        class="w-6 h-6 fill-navy"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"
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
    </div>
</template>

<style scoped>
/* ── Waves ── */
.wave-layer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 140px;
    overflow: hidden;
    pointer-events: none;
}
.wave-shape {
    position: absolute;
    bottom: 0;
    width: 220%;
    height: 100px;
    background: rgba(26, 104, 150, 0.14);
    border-radius: 50% 50% 0 0 / 70px 70px 0 0;
    animation: waveroll 9s ease-in-out infinite;
}
.wave-shape:nth-child(2) {
    background: rgba(19, 53, 88, 0.22);
    height: 70px;
    animation-duration: 13s;
    animation-delay: -4s;
}
.wave-shape:nth-child(3) {
    background: rgba(12, 30, 53, 0.55);
    height: 46px;
    animation-duration: 17s;
    animation-delay: -8s;
}
@keyframes waveroll {
    0%,
    100% {
        transform: translateX(-30%) scaleY(1);
    }
    50% {
        transform: translateX(0%) scaleY(1.1);
    }
}

/* ── Ship ── */
.ship-float {
    animation: shipbob 7s ease-in-out infinite;
}
@keyframes shipbob {
    0%,
    100% {
        transform: translateX(-50%) translateY(0);
    }
    50% {
        transform: translateX(-50%) translateY(-12px);
    }
}

/* ── Scroll hint ── */
.pulse-scroll {
    animation: fadepulse 2.5s ease-in-out infinite;
}
@keyframes fadepulse {
    0%,
    100% {
        opacity: 0.4;
    }
    50% {
        opacity: 1;
    }
}

/* ── Stars ── */
.stars {
    background-image:
        radial-gradient(
            1px 1px at 8% 12%,
            rgba(255, 255, 255, 0.55) 0%,
            transparent 100%
        ),
        radial-gradient(
            1px 1px at 22% 6%,
            rgba(255, 255, 255, 0.4) 0%,
            transparent 100%
        ),
        radial-gradient(
            1.5px 1.5px at 40% 16%,
            rgba(255, 255, 255, 0.65) 0%,
            transparent 100%
        ),
        radial-gradient(
            1px 1px at 60% 5%,
            rgba(255, 255, 255, 0.35) 0%,
            transparent 100%
        ),
        radial-gradient(
            1px 1px at 76% 18%,
            rgba(255, 255, 255, 0.45) 0%,
            transparent 100%
        ),
        radial-gradient(
            1px 1px at 90% 8%,
            rgba(255, 255, 255, 0.55) 0%,
            transparent 100%
        ),
        radial-gradient(
            1px 1px at 50% 10%,
            rgba(255, 255, 255, 0.5) 0%,
            transparent 100%
        );
}

/* ── Nav link underline ── */
.nav-link {
    position: relative;
    text-decoration: none;
}
.nav-link::after {
    content: "";
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 1px;
    background: #c4893a;
    transform: scaleX(0);
    transition: transform 0.2s ease;
}
.nav-link:hover::after {
    transform: scaleX(1);
}

/* ── Hamburger lines ── */
.hamburger-line {
    display: block;
    width: 18px;
    height: 1.5px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 1px;
    transition:
        transform 0.25s ease,
        opacity 0.2s ease;
    transform-origin: center;
}

/* ── Mobile drawer ── */
.mobile-drawer-open {
    max-height: 600px;
}
.mobile-drawer-closed {
    max-height: 0;
}
.mobile-nav-item {
    border-radius: 4px;
}

/* ── Cards ── */
.card-hover {
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}
.card-hover:hover {
    transform: translateY(-6px);
    box-shadow: 0 24px 56px rgba(12, 30, 53, 0.13);
}

/* ── Type sizes ── */
.hero-title {
    font-size: clamp(3rem, 7.5vw, 5.8rem);
}
.section-title {
    font-size: clamp(1.9rem, 3.5vw, 2.7rem);
}
.stat-num {
    font-size: 2.6rem;
}

/* ── Package ── */
.pkg-featured {
    background: rgba(196, 137, 58, 0.12);
}

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

/* ── Form inputs (kept from original) ── */
.form-input {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 2px;
    padding: 12px 14px;
    font-family: "Inter", system-ui, sans-serif;
    font-size: 0.875rem;
    color: white;
    width: 100%;
    outline: none;
    transition:
        border-color 0.2s,
        box-shadow 0.2s;
}
.form-input::placeholder {
    color: rgba(255, 255, 255, 0.25);
}
.form-input:focus {
    border-color: #c4893a;
    box-shadow: 0 0 0 3px rgba(196, 137, 58, 0.15);
}
select.form-input option {
    background: #0c1e35;
}
</style>
