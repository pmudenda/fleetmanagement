const vueDataStore = {

  persistObject(key, value) {
    return localStorage.setItem(key, JSON.stringify(value));
  },
  persist(key, value) {
    return localStorage.setItem(key, value);
  },
  get(key) {

    return localStorage.getItem(key);
  },
  getObject(key) {
    return JSON.parse(localStorage.getItem(key));
  },
  remove(key) {
    return localStorage.removeItem(key);
  },

  clearAll() {
    localStorage.clear();
  }
};

export default vueDataStore;

