<template>
    <div class="portfolio-info">
        <div class="container">
            <div class="row">

                <portfolio-info-block :col-num="colNum" :title="'Year'" v-if="year">{{ year }}</portfolio-info-block>
                <portfolio-info-block :col-num="colNum" :title="'Client'" v-if="client">{{ client }}</portfolio-info-block>
                <portfolio-info-block :col-num="colNum" :title="'Website'" v-if="website"><a :href="website" target="_blank">{{ site_domain }}</a></portfolio-info-block>

            </div>
        </div>
    </div>
</template>

<script>
    import PortfolioInfoBlock from "./PortfolioInfoBlock";
    export default {
        components: {PortfolioInfoBlock},
        props: {
            year: {
                required: true,
            },
            client: {
                required: true,
            },
            website: {
                required: true,
            },
        },

        computed: {
            site_domain() {
                const url = this.website;

                if (!url) {
                    return null;
                }

                return url.split(/\/+/)[1];
            },

            colNum: function () {
                let count = 0;

                if (this.year) {
                    count++;
                }

                if (this.client) {
                    count++;
                }

                if (this.website) {
                    count++;
                }

                if (!count) {
                    return 0;
                }

                return 12 / count;
            }
        },
    }
</script>

<style scoped>

</style>
