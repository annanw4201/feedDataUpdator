<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <form>
                    <div class="form-group row">
                        <label class="sr-only col-sm-2 col-form-label" for="new-date">New Date</label>
                        <div class="input-group col-sm-4">
                            <div class="input-group-prepend">
                                <div class="input-group-text">New Date</div>
                            </div>
                            <input type="text" class="form-control" id="new-date" placeholder="New Date">
                        </div>
                        <button class="btn btn-info">Submit</button>
                    </div>

                    <template v-for="(fundData, fundId) in staleData">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label :for="fundId" class="col-sm-4 col-form-label">{{ fundData.name }}</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" :id="fundId" :placeholder="'AUM: ' + fundData.aum">
                                    </div>
                                    <div class="col-sm-4"></div>
                                    <template v-for="(seriesData, seriesId) in fundData.series">
                                        <div class="col-sm-4"></div>
                                        <label :for="seriesId" class="col-sm-4 col-form-label">{{ seriesId }}</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" :id="seriesId" :placeholder="'NAV: ' + seriesData.latest_nav.value">
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
export default {
    name: "FeedData",
    props: {
        data: {}
    },
    data() {
        return {
            staleData: this.data.staleData ?? {}
        }
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>

<style scoped>

</style>
