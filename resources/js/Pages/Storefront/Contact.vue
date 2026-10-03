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

const pageSchema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type': 'BreadcrumbList',
            itemListElement: [
                { '@type': 'ListItem', position: 1, name: 'Beranda', item: origin.value || '/' },
                { '@type': 'ListItem', position: 2, name: 'Kontak & Pengiriman', item: props.canonicalUrl },
            ],
        },
        {
            '@type': 'Florist',
            name: store.value.name,
            telephone: store.value.phone ? `+${store.value.phone}` : undefined,
            address: {
                '@type': 'PostalAddress',
                streetAddress: store.value.address,
                addressLocality: 'Jakarta',
                addressRegion: 'DKI Jakarta',
                addressCountry: 'ID',
            },
            areaServed: [
                'DKI Jakarta',
                'Jakarta Barat',
                'Jakarta Pusat',
                'Jakarta Selatan',
                'Jakarta Timur',
                'Jakarta Utara',
                'Bogor',
                'Depok',
                'Tangerang',
                'Tangerang Selatan',
                'Bekasi',
            ],
        },
    ],
}).replace(/</g, '\\u003c'));

const whatsappUrl = computed(() => {
    if (!store.value.phone) return null;
    return `https://wa.me/${store.value.phone}?text=${encodeURIComponent('Halo ' + store.value.name + ', saya ingin bertanya mengenai pemesanan & pengiriman bunga.')}`;
});
</script>

<template>
    <Head :title="`Kontak & Area Pengiriman Jabodetabek | ${store.name}`">
        <meta name="description" :content="`Hubungi ${store.name}, toko bunga Jakarta. Layanan pengiriman bunga papan, buket & standing flower ke seluruh area Jabodetabek (Jakarta, Bogor, Depok, Tangerang, Bekasi). Pesan via WhatsApp.`" />
        <link rel="canonical" :href="canonicalUrl" />
        <meta property="og:title" :content="`Kontak & Area Pengiriman Jabodetabek | ${store.name}`" />
        <meta property="og:description" :content="`Hubungi ${store.name}, toko bunga Jakarta. Pengiriman cepat untuk area Jakarta, Bogor, Depok, Tangerang, dan Bekasi.`" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" :content="defaultOgImage" />
    </Head>

    <SeoJsonLd :content="pageSchema" />

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
                    <li aria-current="page" class="font-bold text-foreground">Kontak & Area Pengiriman</li>
                </ol>
            </nav>
            <div class="mt-4">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">
                    Hubungi Kami & Layanan Antar
                </span>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">
                    Kontak & Jangkauan Pengiriman
                </h1>
                <p class="mt-3 max-w-2xl text-base text-muted-foreground">
                    Toko bunga Jakarta dengan jangkauan pengiriman cepat bunga segar dan bunga papan ke seluruh wilayah Jabodetabek.
                </p>
            </div>
        </div>
    </section>

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">

                <!-- Info Kontak -->
                <div class="space-y-8">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-primary">Informasi Toko</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                            Cara Menghubungi Kami
                        </h2>
                        <p class="mt-3 text-base leading-relaxed text-muted-foreground">
                            Kami siap membantu Anda memilih rangkaian bunga yang tepat untuk setiap momen. Hubungi kami melalui WhatsApp untuk konsultasi cepat dengan florist kami.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <!-- WhatsApp -->
                        <div v-if="store.phone" class="flex items-start gap-4 rounded-2xl border border-border/70 bg-card p-5 shadow-xs">
                            <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-green-500/10 text-green-600">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.19 14.91 19.79 19.79 0 0 1 1.12 6.24 2 2 0 0 1 3.1 4h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-muted-foreground">WhatsApp Resmi</p>
                                <p class="mt-0.5 text-base font-semibold text-foreground">+{{ store.phone }}</p>
                                <a
                                    :href="whatsappUrl!"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-green-500 px-3.5 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-green-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2"
                                >
                                    Chat Sekarang
                                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div v-if="store.address" class="flex items-start gap-4 rounded-2xl border border-border/70 bg-card p-5 shadow-xs">
                            <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Alamat Toko</p>
                                <p class="mt-0.5 text-base font-semibold text-foreground">{{ store.address }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">Jakarta, Indonesia</p>
                            </div>
                        </div>

                        <!-- Jam Buka -->
                        <div v-if="store.hours" class="flex items-start gap-4 rounded-2xl border border-border/70 bg-card p-5 shadow-xs">
                            <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Jam Operasional</p>
                                <p class="mt-0.5 text-base font-semibold text-foreground">{{ store.hours }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">Buka setiap hari (termasuk tanggal merah / hari libur)</p>
                            </div>
                        </div>

                        <!-- Layanan Same Day -->
                        <div class="rounded-2xl border border-primary/20 bg-primary/5 p-5">
                            <h3 class="flex items-center gap-2 text-sm font-semibold text-foreground">
                                <span class="size-2 rounded-full bg-green-500 animate-pulse"></span>
                                Layanan Pengiriman Same Day
                            </h3>
                            <p class="mt-1.5 text-xs leading-relaxed text-muted-foreground">
                                Butuh karangan bunga atau buket hari ini? Kami menyediakan opsi <em>Same Day Delivery</em> untuk area Jakarta dan Bodetabek dengan konfirmasi pesanan lebih awal melalui WhatsApp.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Area Pengiriman Jabodetabek -->
                <div>
                    <div class="rounded-3xl border border-border/70 bg-card p-6 shadow-xs sm:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">
                                Jangkauan Pengiriman
                            </span>
                            <span class="text-xs font-semibold text-muted-foreground">
                                Pengiriman Tiap Hari
                            </span>
                        </div>
                        <h2 class="mt-3 text-2xl font-semibold tracking-tight">
                            Area Pengiriman Jabodetabek
                        </h2>
                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                            Kami melayani pengiriman bunga segar, buket, standing flower, dan bunga papan ke seluruh wilayah Jakarta, Bogor, Depok, Tangerang, dan Bekasi.
                        </p>

                        <!-- Group: DKI Jakarta -->
                        <div class="mt-6">
                            <h3 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-foreground">
                                <span class="size-2 rounded-full bg-primary"></span>
                                DKI Jakarta
                            </h3>
                            <div class="mt-3 grid gap-2.5 sm:grid-cols-2">
                                <div class="flex items-start gap-3 rounded-xl bg-primary/5 p-3">
                                    <span class="mt-0.5 flex size-4 shrink-0 items-center justify-center rounded-full bg-primary text-[9px] font-bold text-primary-foreground">✓</span>
                                    <div>
                                        <p class="text-xs font-bold text-foreground">Jakarta Barat</p>
                                        <p class="text-[11px] font-semibold text-primary">Gratis Ongkir</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-xl bg-primary/5 p-3">
                                    <span class="mt-0.5 flex size-4 shrink-0 items-center justify-center rounded-full bg-primary text-[9px] font-bold text-primary-foreground">✓</span>
                                    <div>
                                        <p class="text-xs font-bold text-foreground">Jakarta Pusat</p>
                                        <p class="text-[11px] font-semibold text-primary">Gratis Ongkir</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-xl border border-border/70 bg-background/50 p-3">
                                    <span class="mt-0.5 flex size-4 shrink-0 items-center justify-center rounded-full bg-secondary text-[9px] font-bold text-foreground">✓</span>
                                    <div>
                                        <p class="text-xs font-semibold text-foreground">Jakarta Selatan</p>
                                        <p class="text-[11px] text-muted-foreground">Tersedia (ongkir terjangkau)</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-xl border border-border/70 bg-background/50 p-3">
                                    <span class="mt-0.5 flex size-4 shrink-0 items-center justify-center rounded-full bg-secondary text-[9px] font-bold text-foreground">✓</span>
                                    <div>
                                        <p class="text-xs font-semibold text-foreground">Jakarta Timur</p>
                                        <p class="text-[11px] text-muted-foreground">Tersedia (ongkir terjangkau)</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-xl border border-border/70 bg-background/50 p-3 sm:col-span-2">
                                    <span class="mt-0.5 flex size-4 shrink-0 items-center justify-center rounded-full bg-secondary text-[9px] font-bold text-foreground">✓</span>
                                    <div>
                                        <p class="text-xs font-semibold text-foreground">Jakarta Utara</p>
                                        <p class="text-[11px] text-muted-foreground">Tersedia (ongkir terjangkau)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Group: Bodetabek -->
                        <div class="mt-6 border-t border-border/70 pt-5">
                            <h3 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-foreground">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                Bodetabek (Bogor, Depok, Tangerang, Bekasi)
                            </h3>
                            <div class="mt-3 grid gap-2.5 sm:grid-cols-2">
                                <div class="rounded-xl border border-border/70 bg-background/50 p-3">
                                    <div class="flex items-center gap-2">
                                        <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-secondary text-[9px] font-bold text-foreground">✓</span>
                                        <p class="text-xs font-bold text-foreground">Tangerang & Tangsel</p>
                                    </div>
                                    <p class="mt-1 text-[11px] leading-relaxed text-muted-foreground">
                                        BSD, Serpong, Karawaci, Ciputat, Bintaro, Ciledug, Alam Sutera, Gading Serpong
                                    </p>
                                </div>
                                <div class="rounded-xl border border-border/70 bg-background/50 p-3">
                                    <div class="flex items-center gap-2">
                                        <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-secondary text-[9px] font-bold text-foreground">✓</span>
                                        <p class="text-xs font-bold text-foreground">Kota & Kab. Bekasi</p>
                                    </div>
                                    <p class="mt-1 text-[11px] leading-relaxed text-muted-foreground">
                                        Bekasi Barat, Bekasi Timur, Tambun, Cikarang, Harapan Indah, Summarecon
                                    </p>
                                </div>
                                <div class="rounded-xl border border-border/70 bg-background/50 p-3">
                                    <div class="flex items-center gap-2">
                                        <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-secondary text-[9px] font-bold text-foreground">✓</span>
                                        <p class="text-xs font-bold text-foreground">Kota Depok</p>
                                    </div>
                                    <p class="mt-1 text-[11px] leading-relaxed text-muted-foreground">
                                        Margonda, Cinere, Sawangan, Cimanggis, Beji, Sukmajaya, Pancoran Mas
                                    </p>
                                </div>
                                <div class="rounded-xl border border-border/70 bg-background/50 p-3">
                                    <div class="flex items-center gap-2">
                                        <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-secondary text-[9px] font-bold text-foreground">✓</span>
                                        <p class="text-xs font-bold text-foreground">Kota & Kab. Bogor</p>
                                    </div>
                                    <p class="mt-1 text-[11px] leading-relaxed text-muted-foreground">
                                        Kota Bogor, Sentul City, Cibinong, Bojonggede, Cileungsi
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Keunggulan Pengiriman -->
                        <div class="mt-5 rounded-2xl bg-secondary/40 p-4">
                            <div class="flex items-start gap-3">
                                <span class="text-base" aria-hidden="true">🚚</span>
                                <div class="text-xs leading-relaxed text-muted-foreground">
                                    <strong class="text-foreground">Kurir Khusus Bunga:</strong> Rangkaian bunga papan diantar dengan kendaraan bak tertutup/khusus sehingga aman dari panas dan hujan, tiba tegak dan rapi di lokasi tujuan.
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="mt-6 border-t border-border/70 pt-5">
                            <p class="text-xs text-muted-foreground">
                                Butuh informasi tarif ongkir atau jadwal pengiriman ke lokasi Anda di Jabodetabek? Hubungi kami langsung via WhatsApp.
                            </p>
                            <a
                                v-if="whatsappUrl"
                                :href="whatsappUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-4 inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 text-sm font-semibold text-primary-foreground shadow-md transition-all duration-200 hover:bg-primary/95 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                            >
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.19 14.91 19.79 19.79 0 0 1 1.12 6.24 2 2 0 0 1 3.1 4h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                                <span>Cek Ongkir & Jadwal via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
