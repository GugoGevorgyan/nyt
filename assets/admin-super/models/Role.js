/** @format */

import Model from '../base/Model';
import Module from './Module';

export default class Role extends Model {
    static MODE_CREATE = 1;

    static MODE_UPDATE = 2;

    static MODE_DELETE = 3;

    except = ['module'];

    rules = {
        module_id: {
            required: true,
            integer: true,
            exists: {
                table: 'modules',
                col: 'module_id',
            },
        },
        alias: 'required|string|max:255',
        name: 'required|string|max:255',
        guard_name: 'required|string|max:255',
        description: 'string',
    };

    constructor(role = {}) {
        super('roles', 'admin/super');

        this.role_id = role.role_id || null;
        this.module_id = role.module_id || null;
        this.name = role.name || '';
        this.alias = role.alias || '';
        this.guard_name = role.guard_name || '';
        this.description = role.description || '';
        this.module(role.module || {});
    }

    /**
     * WorkerRole hasOne Module relation
     *
     * @param {Object} module
     * @return {*}
     */
    module(module = {}) {
        return this.hasOne(Module, module);
    }
}
