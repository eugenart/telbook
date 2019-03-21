<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body table-responsive p-0">
                    <table class="table">
                        <thead>
                        <th style="padding: 0px; border-top: none" width="40%">
                        </th>
                        <th style="padding: 0px; border-top: none" width="40%">
                        </th>
                        <th style="padding: 0px; border-top: none" width="20%">
                        </th>
                        </thead>
                        <tbody v-for="(building, key) in buildings">
                        <tr>
                            <th colspan="3" v-if="key == 1"><h5><b>Учебные корпуса</b></h5></th>
                            <th colspan="3" v-if="key == 2"><h5><b>Общежития</b></h5></th>
                            <th colspan="3" v-if="key == 3"><h5><b>Другие строения</b></h5></th>
                        </tr>
                        <tr v-for="build in orderedItems(building)" :key="build.position">
                            <td>{{ build.name }}</td>
                            <td>{{ build.address }}</td>
                            <td><a :href="'tel:' + cityTypes[build.phone.phonePrefix-1] +
                                + build.phone.phoneNumber ">{{ cityTypes[build.phone.phonePrefix-1] }}
                                {{ build.phone.phoneNumber }}</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                cityTypes: [
                    '+7(8342)',
                    '+7(83451)',
                    '+7(83453)'
                ],
                buildings: {}
            }
        },

        methods: {
            loadBuildings() {
                axios.get('/api/building').then(({data}) => (this.buildings = data));
            },

            orderedItems(items) {
                return _.orderBy(items, 'position', 'asc')
            },
            orderedBuildings(items) {
                return _.orderBy(items, 'id', 'desc')
            },
        },

        created() {
            this.loadBuildings();
        }

    }
</script>

<style scoped>

</style>
