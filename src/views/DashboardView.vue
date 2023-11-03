<script setup>
import StudipIcon from '../components/base/StudipIcon.vue';
</script>

<template>
    <main>
        <header>
            <h2>{{ $gettext('Deine Kartensätze') }}</h2>
        </header>

        <form class="default" @submit.prevent="">
            <div class="formpart">
                <label>
                    <span class="textlabel">{{ $gettext('Kartensätze filtern') }}</span>
                    <input type="text" name="lernkarten-decks-filter" />
                </label>
            </div>

            <div class="formpart">
                <button type="button" class="button">
                    <StudipIcon shape="add" />{{ $gettext('Neuer Kartensatz') }}
                </button>
            </div>

            <div class="formpart">
                <select>
                    <option>Alle Ordner</option>
                    <option>Ohne Ordner</option>
                    <option disabled>_________</option>
                    <option>Ordner verwalten</option>
                </select>
            </div>
        </form>

        <section>
            <article>
                <div class="kartensatz-block tw-border tw-h-[100-px] tw-flex">
                    <div class="stats-area tw-w-[100px] tw-h-[100px] tw-bg-slate-300">
                        <div class="kartensatz-block-prozent tw-px-4">0%</div>
                    </div>

                    <div class="text-area tw-grow tw-flex tw-flex-col">
                        <p class="title">XXX</p>
                        <p class="sub-title ellipsis">&nbsp;</p>

                        <div class="sub-info tw-flex tw-flex-row">
                            <div class="wrapper">
                                <StudipIcon shape="dialog-cards" role="info" />
                                <span>5</span>
                            </div>

                            <button
                                id="lernenmobil-2426807"
                                class="bright-link learn-btn"
                                type="button"
                            >
                                <StudipIcon shape="refresh" role="info" />
                                <span class="show-for-tablet-up-inline">Lernen</span>
                            </button>
                            <div class="settings-btn-container">
                                <button
                                    style="width: 20px"
                                    title="Einstellungen"
                                    class="settings-btn"
                                    id="settings-2426807"
                                >
                                    <StudipIcon shape="menu-more" role="info" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </main>
</template>
