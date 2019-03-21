<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-3">
                            <thead>
                            <th style="padding: 0px; border-top: none" width="45%">
                            </th>
                            <th style="padding: 0px; border-top: none" width="35%">
                            </th>
                            <th style="padding: 0px; border-top: none" width="20%">
                            </th>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="4">
                                    <h5><b>Телефоны экстренных служб</b></h5>
                                </td>
                            </tr>
                            <tr>
                                <td>Eдиная служба спасения</td>
                                <td><a href="tel:112">112</a></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Полиция</td>
                                <td><a href="tel:02">02</a></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cкорая помощь</td>
                                <td><a href="tel:03">03</a></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Аварийная газовая служба</td>
                                <td><a href="tel:04">04</a></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <tbody>
                            <tr>
                                <td colspan="4"><h5><b>Дежурные службы университета</b></h5></td>
                            </tr>
                            <tr class="thead-light text-small-min">
                                <th style="border-top: none" width="45%" scope="col"></th>
                                <th style="border-top: none;" class="text-center text-sm-left" width="35%" scope="col">
                                    Внешний номер
                                </th>
                                <th style="border-top: none" width="20%" scope="col">Внутренний номер</th>
                            </tr>

                            <tr v-for="em in emergency" class="text-small-min">
                                <td>{{ em.name }}</td>
                                <td class="text-center text-sm-left"><span
                                    v-for="(phn, index) in em.phone"
                                    v-if="em.phone">
                                    <a :href="'tel:'+cityTypes[em.phone[index].phonePrefix -1]+em.phone[index].phoneNumber">{{ cityTypes[em.phone[index].phonePrefix -1] }}
                            {{ em.phone[index].phoneNumber }}</a>
                                     <br>
                            </span></td>
                                <td><span v-for="iphn in em.ip_phone">{{ iphn }}<br> </span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
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
                emergency: {}
            }
        },

        methods: {
            loadEmergency() {
                axios.get('/api/emergency').then(({data}) => (this.emergency = data));
            }
        },

        created() {
            this.loadEmergency();
        }

    }
</script>

<style scoped>
    @media (max-width: 450px) {
        .text-small-min {
            font-size: 12px !important;
        }
    }

</style>
