<script setup lang="ts">
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import SeoJsonLd from '@/Components/SeoJsonLd.vue';
import type { StoreInfo } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: StorefrontLayout });

const props = defineProps<{
    canonicalUrl: string;
}>();

const page = usePage<{ store?: StoreInfo; defaultOgImage?: string }>();
const store = computed<StoreInfo>(() => page.props.store ?? {
    name: 'Otim Florist',
    phone: '',
    address: 'Jakarta',
    hours: '08:00–20:00',
});
const defaultOgImage = computed(() => page.props.defaultOgImage || '/images/og-image.jpg');

const origin = computed(() => {
    try { return new URL(props.canonicalUrl).origin; } catch { return ''; }
});

const breadcrumbSchema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: [
        { '@type': 'ListItem', position: 1, name: 'Beranda', item: origin.value || '/' },
        { '@type': 'ListItem', position: 2, name: 'Tentang Kami', item: props.canonicalUrl },
    ],
}).replace(/</g, '\\u003c'));

const orgSchema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Florist',
    name: store.value.name || 'Otim Florist',
    description: 'Toko bunga Jakarta terpercaya. Menyediakan buket bunga segar, bunga papan, standing flower, dan dekorasi untuk pernikahan, wisuda, duka cita, dan setiap perayaan bermakna.',
    url: origin.value || '/',
    telephone: store.value.phone || undefined,
    address: {
        '@type': 'PostalAddress',
        streetAddress: store.value.address || 'Jakarta',
        addressLocality: 'Jakarta',
        addressRegion: 'DKI Jakarta',
        addressCountry: 'ID',
    },
    openingHours: store.value.hours || undefined,
    priceRange: '$$',
    image: [defaultOgImage.value],
}).replace(/</g, '\\u003c'));
</script>

<template>
    <Head :title="`Tentang Kami — Toko Bunga Jakarta | ${store.name}`">
        <meta name="description" content="Kenali Otim Florist, toko bunga Jakarta yang berpengalaman dalam merangkai buket segar, bunga papan, dan standing flower. Melayani pengiriman seluruh Jakarta." />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" :content="`Tentang Kami — Toko Bunga Jakarta | ${store.name}`" />
        <meta property="og:description" content="Kenali Otim Florist, toko bunga Jakarta yang berpengalaman dalam merangkai buket segar, bunga papan, dan standing flower. Melayani pengiriman seluruh Jakarta." />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" :content="defaultOgImage" />
    </Head>

    <SeoJsonLd :content="breadcrumbSchema" />
    <SeoJsonLd :content="orgSchema" />

    <section class="border-b border-border/80 bg-gradient-to-b from-secondary/50 to-secondary/20">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
            <nav aria-label="Jejak navigasi">
                <ol class="flex min-h-11 items-center gap-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                    <li>
                        <Link href="/" class="rounded-lg transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            Beranda
                        </Link>
                    </li>
                    <li aria-hidden="true" class="text-border">/</li>
                    <li aria-current="page" class="font-bold text-foreground">Tentang Kami</li>
                </ol>
            </nav>
            <div class="mt-4">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">
                    Siapa Kami
                </span>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">
                    Tentang {{ store.name }}
                </h1>
                <p class="mt-3 max-w-2xl text-base text-muted-foreground">
                    Toko bunga Jakarta yang merangkai setiap momen dengan keindahan dan ketulusan.
                </p>
            </div>
        </div>
    </section>

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-16">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-primary">Cerita Kami</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">
                        Merangkai bunga dengan sepenuh hati sejak awal berdiri
                    </h2>
                    <div class="mt-6 space-y-4 text-base leading-relaxed text-foreground/80">
                        <p>
                            <strong>{{ store.name }}</strong> adalah toko bunga Jakarta yang berdedikasi menghadirkan rangkaian bunga segar berkualitas untuk setiap momen dalam hidup Anda. Dari buket bunga romantis, bunga papan peresmian, standing flower perayaan, hingga rangkaian duka cita — kami merangkai semuanya dengan ketelitian dan penuh perhatian.
                        </p>
                        <p>
                            Setiap rangkaian yang kami buat dipilih dari bunga segar pilihan, dikomposisikan secara artistik oleh florist berpengalaman kami. Kami percaya bahwa bunga bukan sekadar hiasan — bunga adalah bahasa perasaan yang melampaui kata-kata.
                        </p>
                        <p>
                            Berlokasi di Jakarta, kami melayani pengiriman ke seluruh wilayah Jakarta dengan layanan gratis ongkir khusus area Jakarta Barat dan Jakarta Pusat. Setiap pesanan dikerjakan dengan standar kualitas tertinggi untuk memastikan bunga sampai dalam kondisi segar dan sempurna.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col justify-end rounded-3xl bg-primary/10 p-6">
                        <p class="text-4xl font-bold text-primary">5★</p>
                        <p class="mt-1 text-sm font-medium text-foreground">Rating Google</p>
                        <p class="text-xs text-muted-foreground">Dipercaya pelanggan Jakarta</p>
                    </div>
                    <div class="rounded-3xl border border-border/70 bg-card p-6 shadow-xs">
                        <p class="text-4xl font-bold text-primary">100%</p>
                        <p class="mt-1 text-sm font-medium text-foreground">Bunga Segar</p>
                        <p class="text-xs text-muted-foreground">Dipilih di hari pengiriman</p>
                    </div>
                    <div class="rounded-3xl border border-border/70 bg-card p-6 shadow-xs">
                        <p class="text-4xl font-bold text-primary">Free</p>
                        <p class="mt-1 text-sm font-medium text-foreground">Ongkir Jakbar & Jakpus</p>
                        <p class="text-xs text-muted-foreground">Pengiriman aman & tepat waktu</p>
                    </div>
                    <div class="flex flex-col justify-end rounded-3xl bg-secondary p-6">
                        <p class="text-4xl font-bold text-foreground">🌸</p>
                        <p class="mt-1 text-sm font-medium text-foreground">Beragam Rangkaian</p>
                        <p class="text-xs text-muted-foreground">Buket, papan, standing, dekorasi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-border/70 bg-secondary/30 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary">Komitmen Kami</p>
                <h2 class="mt-2 text-3xl font-semibold sm:text-4xl">Mengapa Memilih {{ store.name }}?</h2>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex flex-col rounded-3xl border border-border/70 bg-card p-7 shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" /></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Florist Berpengalaman</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">Dirangkai oleh florist terlatih dengan pengalaman bertahun-tahun. Setiap detail diperhatikan untuk menghasilkan tampilan yang memukau.</p>
                </div>
                <div class="flex flex-col rounded-3xl border border-border/70 bg-card p-7 shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Bunga Segar Pilihan</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">Hanya menggunakan bunga segar berkualitas yang dipilih dari kuncup terbaik pada hari pengiriman, menjamin kesegaran bunga Anda.</p>
                </div>
                <div class="flex flex-col rounded-3xl border border-border/70 bg-card p-7 shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" /><circle cx="12" cy="10" r="3" /></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Pengiriman Aman Jakarta</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">Kurir terlatih memastikan bunga sampai tegak dan segar. Gratis ongkir area Jakarta Barat dan Jakarta Pusat.</p>
                </div>
                <div class="flex flex-col rounded-3xl border border-border/70 bg-card p-7 shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" /></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Gratis Kartu Ucapan</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">Setiap pesanan disertai kartu ucapan gratis. Tuliskan pesan manis Anda saat konfirmasi via WhatsApp.</p>
                </div>
                <div class="flex flex-col rounded-3xl border border-border/70 bg-card p-7 shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.19 14.91 19.79 19.79 0 0 1 1.12 6.24 2 2 0 0 1 3.1 4h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" /></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Pesan Mudah via WhatsApp</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">Proses pemesanan cepat dan mudah melalui WhatsApp. Konsultasikan kebutuhan bunga Anda langsung dengan florist kami.</p>
                </div>
                <div class="flex flex-col rounded-3xl border border-border/70 bg-card p-7 shadow-xs transition-shadow hover:shadow-md">
                    <div class="grid size-12 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" /></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Harga Transparan</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">Harga yang tercantum adalah harga final tanpa biaya tersembunyi. Nilai terbaik untuk setiap rangkaian bunga Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-border/70 py-16 sm:py-20">
        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-semibold tracking-tight sm:text-4xl">
                Siap memesan bunga untuk momen Anda?
            </h2>
            <p class="mt-4 text-base text-muted-foreground">
                Jelajahi koleksi bunga segar kami atau hubungi florist kami langsung via WhatsApp untuk konsultasi rangkaian khusus.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <Link href="/" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-primary px-6 text-sm font-semibold text-primary-foreground shadow-md transition-all duration-200 hover:bg-primary/95 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                    Lihat Koleksi Bunga
                </Link>
                <Link href="/kontak" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-border bg-card px-6 text-sm font-semibold text-foreground shadow-xs transition-colors duration-200 hover:bg-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                    Hubungi Kami
                </Link>
            </div>
        </div>
    </section>
</template>
