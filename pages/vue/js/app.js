const app = {
    deleteMobileNumber: function () {
        localStorage.removeItem('mobile')
    }
}

console.log(axios);

const vm = Vue.createApp({
    data() {
        return {
            name: 'My App',
            loader: false,
        }
    },
    methods: {
        onRegisterFormSubmit() {
            this.loader = true;
            axios.get(apiUrl + '?route=app.version')
                .then(function (res) {
                    console.log('res: ', res.data);
                });
        }
    }
});

vm.component('app-loader', {
    props: ['text'],
    template: `
        <div class="flex justify-content-center mt-3">
            <div class="spinner"></div>
            <div class="ml-2">{{ text }}</div>
        </div>
    `
});

vm.mount('body');
