<template>
    <div class="container">
        <div class="row justify-content-center">

            <input @input="searchIt" autofocus @keyup="searchIt" v-model="search" class="col-12 custom-search"
                   type="search"
                   placeholder="Поиск по справочнику" aria-label="Search">
            <p class="mistake-p mt-2">Если вы обнаружили ошибку, то сообщите об этом в службу технической поддержки Центр
                Интернет по номеру телефона <strong><a href="tel: +7(8342)777-250">+7 (8342) 777-250</a></strong> доб. 1234. </p>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" v-for="man in morePeople">
                <div class="our-services-wrapper mb-60">
                    <div class="services-inner">
                        <div class="our-services-img">
                            <img src="/images/user_telephone.png" width="68px" alt="">
                        </div>
                        <div class="our-services-text">
                            <h4>{{ man.fio }}</h4>
                            <h5>{{ man.group_name }}</h5>
                            <p><i class="fas fa-users"></i> {{ man.position }}</p>
                            <div v-if='man.phone'><span v-for="phn in man.phone"><i class="fas fa-phone"></i>
                                        <a :href="'tel:'+cityTypes[phn.phonePrefix -1]+phn.phoneNumber">{{ cityTypes[phn.phonePrefix -1] }}
                                        {{ phn.phoneNumber }}</a>
                                <br>
                                </span></div>
                            <div v-if="man.ip_phone"><span v-for="iphn in man.ip_phone"><i class="fas fa-fax"></i> {{ iphn }}<br> </span>
                            </div>
                            <div v-if="man.email"><span v-for="ml in man.email"><i class="fas fa-at"></i> <a
                                :href="'mailto:'+ml">{{ ml }}</a><br> </span>
                            </div>
                            <p><i class="fas fa-hotel"></i> {{ man.building_name }}, {{ man.room }} {{
                                roomTypes[man.room_type -1] }} ({{
                                man.building_address }})</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col-12 justify-content-center">
                <button v-if="fetchPeople" class="loadmore-button" @click="loadMorePeople">Загрузить еще</button>
            </div>
            <div class="row col-12 search-message justify-content-center" v-if="!people.length && search!==''">
                <span class="col-12" v-html="message"></span><br>
                <span class="col-12" v-if="newQuery!==''">Возможно вы имели ввиду: <a href=""
                                                                                      @click.prevent="changeData()">{{ newQuery }}</a></span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                roomTypes: [
                    'кабинет',
                    'этаж'
                ],
                cityTypes: [
                    '+7 (8342)',
                    '+7 (83451)',
                    '+7 (83453)'
                ],
                search: '',
                people: '',
                newPeople: '',
                morePeople: '',
                fetchPeople: false,
                message: '',
                newQuery: '',
                map:
                    {
                        'q': 'й',
                        'w': 'ц',
                        'e': 'у',
                        'r': 'к',
                        't': 'е',
                        'y': 'н',
                        'u': 'г',
                        'i': 'ш',
                        'o': 'щ',
                        'p': 'з',
                        '[': 'х',
                        ']': 'ъ',
                        'a': 'ф',
                        's': 'ы',
                        'd': 'в',
                        'f': 'а',
                        'g': 'п',
                        'h': 'р',
                        'j': 'о',
                        'k': 'л',
                        'l': 'д',
                        ';': 'ж',
                        '\'': 'э',
                        'z': 'я',
                        'x': 'ч',
                        'c': 'с',
                        'v': 'м',
                        'b': 'и',
                        'n': 'т',
                        'm': 'ь',
                        ',': 'б',
                        '.': 'ю',
                        'Q': 'Й',
                        'W': 'Ц',
                        'E': 'У',
                        'R': 'К',
                        'T': 'Е',
                        'Y': 'Н',
                        'U': 'Г',
                        'I': 'Ш',
                        'O': 'Щ',
                        'P': 'З',
                        '[': 'Х',
                        ']': 'Ъ',
                        'A': 'Ф',
                        'S': 'Ы',
                        'D': 'В',
                        'F': 'А',
                        'G': 'П',
                        'H': 'Р',
                        'J': 'О',
                        'K': 'Л',
                        'L': 'Д',
                        ';': 'Ж',
                        '\'': 'Э',
                        'Z': '?',
                        'X': 'ч',
                        'C': 'С',
                        'V': 'М',
                        'B': 'И',
                        'N': 'Т',
                        'M': 'Ь',
                        ',': 'Б',
                        '.': 'Ю',
                    }
            }
        },

        methods:
            {
                searchIt: _.debounce(() => {
                    Fire.$emit('searching');
                }, 300),

                replacer(str) {
                    var r = '';
                    for (var i = 0; i < str.length; i++) {
                        r += this.map[str.charAt(i)] || str.charAt(i);
                    }
                    return r;
                },

                changeData() {
                    this.morePeople = '';
                    this.search = this.newQuery;
                    this.people = this.newPeople;
                    this.morePeople = this.people.splice(0, 9);
                    this.message = '';
                    this.newQuery = '';
                    if (this.people.length) {
                        this.fetchPeople = true;
                    } else {
                        this.fetchPeople = false;
                    }
                },

                loadMorePeople() {
                    if (this.fetchPeople) {
                        this.morePeople.push(...this.people.splice(0, 9))
                        if (this.people.length) {
                            this.fetchPeople = true;
                        } else {
                            this.fetchPeople = false;
                        }
                    }
                }


            }
        ,
//loadMorePeople
        created() {
            Fire.$on('searching', () => {
                let query = this.search;
                let newquery = this.replacer(query);
                this.morePeople = '';
                if (query.length) {
                    axios.get('/api/search?q=' + query)
                        .then((data) => {
                            this.newQuery = '';
                            this.message = 'Идет поиск';
                            this.people = data.data;
                            if (this.people == '') {
                                this.fetchPeople = false,
                                    this.message = "По запросу <b><i>" + query + "</i></b> ничего не найдено :( <br>",
                                    axios.get('/api/search?q=' + newquery)
                                        .then((data) => {
                                            this.newPeople = data.data;
                                            if (this.newPeople.length && newquery.length > 2) {
                                                this.newQuery = newquery;
                                                console.log(this.newQuery)
                                            } else {

                                            }

                                        })
                            } else {
                                this.morePeople = this.people.splice(0, 9);
                                this.message = ''
                                if (this.people.length) {
                                    this.fetchPeople = true;
                                } else {
                                    this.fetchPeople = false;
                                }
                            }
                        })
                        .catch((e) => {
                            this.message = 'Что-то пошло не так ' + e;
                        })
                } else {
                    this.people = '';
                    this.fetchPeople = false;
                }
            })
        },


    }
</script>

<style scoped>
    .mistake-p {
        font-size: 14px;
    }

    .mistake-p a{
        font-weight: bold;
    }

    .custom-search {
        font-size: 48px;
        border: none;
        border-bottom: 3px solid rgba(4, 30, 66, 0.9);
        border-radius: 0px;
        outline: none;
    }

    .custom-search::placeholder {
        color: rgba(122, 122, 122, 0.6);
        font-size: 30px;
    }

    .custom-search:focus {
        outline: none;
    }

    .search-message {
        font-size: 30px;
    }

    .loadmore-button {
        border: 3px solid rgba(4, 30, 66, 0.9);
        padding: 10px 50px;
        font-size: 18px;
        font-weight: bolder;
        cursor: pointer;
        background-color: rgba(4, 30, 66, 0.9);
        color: white;
    }

    .loadmore-button:hover {
        border-color: #3f91b6;
        background-color: #3f91b6;
        color: white;
    }

    @media (max-width: 426px) {
        .custom-search {
            margin-top: 40px;
            width: 90%;
            font-size: 24px;
        }

        .custom-search::placeholder {
            font-size: 20px;
        }

        .search-message {
            font-size: 20px;
            margin: auto;
        }
    }
</style>
