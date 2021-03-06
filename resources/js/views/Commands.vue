<template>
    <section class="content">
        <div class="section container">
            <h1 class="title is-1">Command List</h1>

            <p>Below you'll find a list of all <strong>{{ totalCommands == 0 ? '???' : totalCommands }}</strong> commands that Ava has, along with a short description of what each command does.</p>

            <h5 class="subtitle is-5">Command Usage Syntax</h5>
            <ul>
                <li><code>[]</code> optional parameter</li>
                <li><code>&lt;&gt;</code> required parameter</li>
            </ul>
        </div>

        <div v-if="this.commands != null">
            <div class="container">
                <div class="tabs is-toggle is-fullwidth">
                    <ul>
                        <li :class="{ 'is-active': this.selected == null }">
                            <a href="#all" v-on:click="setSelectedCategory('all')">
                                <span>All</span>
                            </a>
                        </li>
                        <li v-for="category in Object.keys(this.commands)" :class="{ 'is-active': selected == category }">
                            <a :href="'#' + category.toLowerCase()" v-on:click="setSelectedCategory(category)">
                                <span>{{ category }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="section container" v-if="this.totalCommands > 0" >
                <div v-for="category in Object.keys(this.sortedCommands)">
                    <command-category
                        :name="category"
                        :category="sortedCommands[category]"
                        @see-also="handleSeeAlsoEvent"
                    />
                </div>
            </div>
        </div>

        <div class="section container has-text-centered" v-else>
            <p class="button is-loading transparent"></p>
        </div>
  </section>
</template>

<script>
    import CommandCategory from '../components/CommandCategory';

    export default {
        mounted() {
            axios('/api/commands').then(response => {
                if (response.status == 200) {
                    this.commands = response.data.data;

                    let parts = window.location.href.split("#");
                    if (parts.length == 2) {
                        let lowercaseKeys = Object.keys(response.data.data)
                            .map(category => category.toLowerCase());

                        parts = parts[1].split(':');

                        if (lowercaseKeys.indexOf(parts[0].toLowerCase()) > -1) {
                            this.setSelectedCategory(parts[0]);
                        }

                        if (parts.length == 2 && this.selected != null) {
                            if (this.commands[this.selected].commands.hasOwnProperty(parts[1])) {
                                this.commands[this.selected].commands[parts[1]].isShown = true;
                            }
                        }
                    }
                }
            }).catch(error => {
                this.commands = null;
            });
        },
        data() {
            return {
                commands: null,
                query: null,
                selected: null,
            };
        },
        methods: {
            setSelectedCategory(category) {
                let result = Object.keys(this.commands).find(name => {
                    return name.toLowerCase() == category.toLowerCase();
                });

                this.selected = result == null || result == undefined ? null : result;
            },
            handleSeeAlsoEvent(event) {
                if (! this.commands.hasOwnProperty(event.category)) {
                    return;
                }

                if (! this.commands[event.category].commands.hasOwnProperty(event.command)) {
                    return;
                }

                this.$set(this.commands[event.category].commands[event.command], 'isShown', true);
                this.setSelectedCategory(event.category);
            }
        },
        computed: {
            sortedCommands() {
                if (this.selected == null) {
                    return this.commands;
                }

                let obj = {};
                obj[this.selected] = this.commands[this.selected];

                return obj;
            },
            totalCommands() {
                if (this.commands == null) {
                    return 0;
                }

                let total = 0;
                for (let category in this.commands) {
                    total += Object.keys(this.commands[category].commands).length;
                }

                return total;
            },
        },
        components: {
            CommandCategory
        }
    }
</script>
