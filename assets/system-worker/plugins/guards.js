/** @format */

import store from "./../store";

export class Guard {
    static get canCreateCandidate() {
        let permissions = store.state.permissions;
    }
}
