<template>
<div id="app">

    <div class="title">
        <h1>Chuck Norris Jokes</h1>
        <a href="https://en.wikipedia.org/wiki/Chuck_Norris" target="_blank"><img class="chuck" src="./assets/chuck.gif" /></a>
    </div>
    <div class="get-jokes">
        <button @click="getRandomJokes">
            <file-import-icon size="14" /><span> Get Random Jokes</span></button>
    </div>

    <hr />

    <div class="data">

        <div class="categories">
            <h4>jokes categories</h4>

            <template v-if="collectionOfJokes.length > 0">
                <input :disabled="collectionOfJokes.length == 0" v-model="filter" type="text" autocomplete="off" placeholder="filter jokes" />
                <ul>
                    <li><span class="toggle-all" @click="toggleCategories"><strong>{{!allCategories ? 'Enable all' : 'Disable all'}}</strong></span></li>

                    <li v-for="(category, index) in categories" :key="index">
                        <input :disabled="collectionOfJokes.length == 0" type="checkbox" :name="category" :id="category" :checked="allCategories">
                        <label :for="category" @click="filterCategory(category)">{{category}}</label>
                    </li>
                </ul>
            </template>
        </div>

        <div class="joke-results">
            <h4>Jokes</h4>

            <template v-if="collectionOfJokes.length > 0">
                <ul>
                    <li v-for="(item, index) of collectionOfJokes" :key="index" v-show="item.data['value'].includes(filter) && (allCategories && item.data['categories'][0]===undefined) || selectedCategories.indexOf(item.data['categories'][0]) > -1">
                        <span><strong>({{index + 1}})</strong> "{{item.data['value']}}"</span>
                        <star-icon @click="addFavourite(item.data['id'], item.data['value'])" class="star-icon" size="12" />
                    </li>
                </ul>
            </template>

            <template v-else-if="isLoading">
                <img class="loader" src="./assets/spinner.gif" />
            </template>

            <template v-if="collectionOfJokes.length > 0 && selectedCategories.length == 0">
                Please enable joke category
            </template>
        </div>

        <div class="favourites">
            <h4>My favourite Jokes</h4>
            <ul>
                <li v-for="(item, index) in favouriteList" :key="index">
                    <star-off-icon @click="removeFavouriteJoke(item.id)" class="star-icon-off" size="12" /> <span>{{item.joke}}</span></li>
            </ul>

        </div>

    </div>

    <Footer title=" © Chuck Norris Jokes | " />
</div>
</template>

<script>
import axios from "axios";
import Footer from './components/Footer.vue';

export default {
    name: 'App',
    components: {
        Footer
    },
    created() {
        this.getCategories();
        this.getFavouriteJokes();
    },
    data() {
        return {
            categories: [],
            selectedCategories: [],
            allCategories: false,
            isLoading: false,
            filter: '',
            collectionOfJokes: [],
            jokesLength: 10,
            favourite: {},
            favouriteList: []
        }
    },

    methods: {
        getCategories() {
            axios.get('https://testing.delovski.net/data/api-categories.php')
                .then(response => this.categories = response.data)
                .catch(function (error) {
                    console.log(error);
                });
        },
        getRandomJokes() {
            this.collectionOfJokes = [];
            this.isLoading = true;
            const url = 'https://testing.delovski.net/data/api-random.php';
            const len = 10;
            const arr = [];

            for (let i = 0; i < len; i++) {
                arr.push(axios.get(url));
            }

            if (arr.length == 10) {
                axios.all(arr)
                    .then(axios.spread((...responses) => {
                        this.collectionOfJokes = responses;
                        this.isLoading = false;
                    }))
                    .catch(function (error) {
                        console.log(error);
                    });
            }
            if (!this.allCategories) {
                this.allCategories = true;
                this.selectedCategories = this.categories.slice();
            }
        },
        filterCategory(value) {
            const index = this.selectedCategories.indexOf(value);
            if (index > -1) {
                this.selectedCategories.splice(index, 1);
            } else {
                this.selectedCategories.includes(value) ? '' : this.selectedCategories.push(value);
            }
        },

        toggleCategories() {
            this.allCategories = !this.allCategories
            if (this.allCategories) {
                this.selectedCategories = this.categories.slice();
            } else {
                this.selectedCategories = [];
            }
        },

        addFavourite(id, joke) {
            if (this.favouriteList.filter(item => item.id === id).length > 0) {
                alert('This joke is already in the favourite list');
                return;
            }

            if (this.favouriteList.length < 5) {
                this.favourite = {};
                this.favourite.id = id;
                this.favourite.joke = joke;
                this.favouriteList.push(this.favourite);

                localStorage.setItem('favouriteJokes', JSON.stringify(this.favouriteList));
            } else {
                alert('You can only keep 5 favourite jokes');
            }
        },

        getFavouriteJokes() {
            if (localStorage.getItem('favouriteJokes')) {
                this.favouriteList = JSON.parse(localStorage.getItem('favouriteJokes'));
            }
        },

        removeFavouriteJoke(id) {
            const index = this.favouriteList.findIndex(item => item.id === id);

            this.favouriteList.splice(index, 1);
            localStorage.setItem('favouriteJokes', JSON.stringify(this.favouriteList));
        }
    }
}
</script>

<style>
* {
    outline: 0;
}

#app {
    /* Color variables */
    --border-color: gray;
    --main-font-color: #2c3e50;
    --main-font-hover-color: black;
    --border-focus-color: darkgoldenrod;
    --main-hover-color: lightyellow;
    --star-hover-color: red;

    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: var(--main-font-color);
    margin-top: 20px;
    width: 100%;
}

.title h1 {
    margin-bottom: 4px;
}

.title .chuck {
    height: 80px;
    width: auto;
}

.get-jokes {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
}

.get-jokes button {
    margin-bottom: 10px;
    cursor: pointer;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    display: flex;
    align-items: center;
}

.get-jokes button:hover {
    font-weight: 600;
}

.data {
    display: flex;
    justify-content: space-between;
    margin-bottom: 100px;
}

.data h4 {
    text-transform: uppercase;
    font-size: 14px;
}

.data .categories {
    width: 180px;
}

.data .favourites {
    width: 240px;
}

.data .categories input {
    padding: 2px 5px;
    border-radius: 5px;
    border: 1px solid var(--border-color);
}

.data .categories input:focus {
    border-color: var(--border-focus-color);
    border-width: 1.5px;
}

.data .categories ul,
.data .joke-results ul,
.data .favourites ul {
    list-style-type: none;
}

.data .categories ul li {
    text-align: left;
    display: flex;
    align-items: center;
    font-size: 0.7em;
}

.data .categories ul li:hover {
    font-weight: 600;
    text-decoration: underline;
}

.data .categories ul li span.toggle-all {
    font-size: 12px;
    cursor: pointer;
    padding-bottom: 4px;
}

.data .categories ul li label {
    cursor: pointer;
}

.data .joke-results {
    max-width: 600px;
    font-size: 0.8em;
    text-align: left;
}

.data .joke-results h4 {
    text-align: center;
}

.data .joke-results .loader {
    width: 100px;
    height: auto;
}

.data .joke-results ul li {
    transition: backround-color 3ms ease-in-out;
    padding: 4px 0;
    border-bottom: 1px dashed var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.data .joke-results ul li span {
    width: 100%;
}

.data .joke-results ul li svg {
    width: 20px;
}

.data .joke-results ul li:hover::before {
    padding-right: 4px;
    content: '\2192';
}

.data .joke-results ul li:hover {
    background-color: var(--main-hover-color);
    color: var(--main-font-hover-color);
}

.data .star-icon-off {
    margin-right: 4px;
}

.data .star-icon:hover,
.data .star-icon-off:hover {
    color: var(--star-hover-color);
    cursor: pointer;
}

.data .favourites {
    margin-right: 20px;
    font-size: 0.8em;
}

.data .favourites ul li {
    display: flex;
    border-bottom: 1px dashed var(--border-color);
    padding-bottom: 2px;
    margin-bottom: 8px;
    text-align: left;
}

.data .favourites ul li span {
    width: 100%;
}

.data .favourites ul li svg {
    width: 20px;
}

/* Responsive mobile/tablet support  */
@media screen and (max-width: 768px) {

    .title h1 {
        font-size: 1.5em;
    }

    .data {
        flex-direction: column;
    }

    .data h4,
    .data .joke-results h4 {
        text-align: left;
    }

    .data .categories {
        order: 2;
    }

    .data .joke-results {
        order: 3;
    }

    .data .favourites {
        width: 100%;
    }

    .data .categories ul {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }

    .data .categories ul li {
        width: 65px;
    }

    .data .joke-results .loader {
        margin-left: 20px;
    }

    .data .joke-results ul,
    .data .favourites ul {
        padding-left: 0;
        font-size: 12px;
        text-align: left;
    }

}
</style>
