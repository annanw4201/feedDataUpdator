<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <form id="stale-data-form">
                    <div class="form-group row">
                        <label class="sr-only col-sm-2 col-form-label" for="new-date">New Date</label>
                        <div class="input-group col-sm-4">
                            <div class="input-group-prepend">
                                <div class="input-group-text">New Date</div>
                            </div>
                            <input required type="date" class="form-control" id="new-date" placeholder="New Date" v-model="newDate">
                        </div>
                        <button class="btn btn-info" type="submit" v-on:click.preventDefault="submitForm()">
                            <template v-if="loading">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Processing...
                            </template>
                            <template v-else>
                                Submit
                            </template>
                        </button>
                    </div>

                    <template v-for="(fundData, fundId) in staleData">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label :for="fundId" class="col-sm-4 col-form-label">{{ fundData.name }}</label>
                                    <div class="input-group col-sm-4">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">AUM</div>
                                        </div>
                                        <input type="number" step="any" class="form-control" :id="fundId" :placeholder="'AUM: ' + fundData.aum" v-model="fundData.aum">
                                    </div>
                                    <div class="col-sm-4"></div>
                                    <template v-for="(seriesData, seriesId) in fundData.series">
                                        <div class="col-sm-4"></div>
                                        <label :for="seriesId" class="col-sm-4 col-form-label">{{ seriesId }}</label>
                                        <div class="input-group col-sm-4">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">NAV</div>
                                            </div>
                                            <input type="number" step="any" class="form-control" :id="seriesId" :placeholder="'NAV: ' + seriesData.latest_nav.value" v-model="seriesData.latest_nav.value">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <br>
                    </template>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment/moment.js";
export default {
    name: "FeedData",
    props: {
        data: {}
    },
    data() {
        return {
            staleData: this.data.staleData ?? {},
            newDate: moment().format('YYYY-MM-DD'),
            loading: false
        }
    },
    mounted() {
        console.log('Component mounted.')
    },
    methods: {
        submitForm() {
            let form = $("#stale-data-form")[0];
            if (!form || !form.checkValidity()) return;
            if (!window.confirm("Are you sure to update?")) return;
            let request = {
                    newData: this.staleData,
                    newDate: this.newDate
                },
                api = "/api/app/feed_data/update_stale_data";
            this.loading = true;
            axios.post(api, request)
                .then(response => {
                    console.log(response.data);
                    window.alert("Update successfully. Check generated CSV and JSON files on server.");
                })
                .catch(error => {
                    if (error?.response) {
                        let errors = error.response.data?.errors,
                            msg = errors ? errors[Object.keys(errors)?.[0]] : null;
                        window.alert(msg ?? error.response.data?.message ?? error.response?.statusText ?? "There are some errors.");
                    }
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
}
</script>

<style scoped>

</style>
