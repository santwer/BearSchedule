export default {
    data: function () {
        return {
            copyMethod: null,
            pasteMethod: null,
            deleteMethod: null,
        }
    },
    methods: {
        hasPaste() {
            return !!this.getFromWebStorage('clipboard');
        },
        saveToWebStorage(key, value) {
            localStorage.setItem(key, value);
        },
        deleteFromWebStorage(key) {
            localStorage.removeItem(key);
        },
        getFromWebStorage(key) {
            return localStorage.getItem(key);
        },
        copyItemToClipboard(item) {
            this.saveToWebStorage('clipboard', JSON.stringify(item));
            this.saveToWebStorage('clipboardTime', new Date().getTime());
        },
        getItemFromClipboard() {
            return JSON.parse(this.getFromWebStorage('clipboard'));
        },
        checkTimeout() {
            setTimeout(() => {
                //if clipboardTime is not set or is older than 10 minutes, delete clipboard
                if (this.getFromWebStorage('clipboardTime')
                    && (new Date().getTime() - this.getFromWebStorage('clipboardTime')) > 30000)
                {
                    this.deleteFromWebStorage('clipboard');
                    this.deleteFromWebStorage('clipboardTime');
                }
                this.checkTimeout();
            }, 1800);
        }
    },
    mounted() {
        this.checkTimeout();

        document.addEventListener('copy', (e) => {
            if(this.copyMethod) {
                this.copyMethod(null);
            }
        });
        document.addEventListener('paste', (e) => {
            if(this.pasteMethod) {
                this.pasteMethod(null);
            }
        });

    }
}
