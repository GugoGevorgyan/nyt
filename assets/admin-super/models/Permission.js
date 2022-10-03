/** @format */

import Role from './Role';
import FranchiseAdmin from './FranchiseAdmin';
import Module from './Module';
import Model from '../base/Model';

export default class Permission extends Model {
    static MODE_CREATE = 1;

    static MODE_UPDATE = 2;

    static MODE_DELETE = 3;

    scope = 'permission';

    rules = {
        role_id: {
            required: true,
            integer: true,
            exists: { table: 'roles', col: 'role_id' },
        },
        name: 'required|string|max:255',
        alias: 'required|string|max:255',
        guard_name: 'required|string|max:255',
        description: 'string',
    };

    except = ['modules', 'admins', 'role'];

    /**
     * Permission model constructor
     *
     * @param {Object} permission
     */
    constructor(permission = {}) {
        super('permissions', 'admin/super');

        this.permission_id = permission.permission_id || null;
        this.name = permission.name || '';
        this.alias = permission.alias || '';
        this.guard_name = permission.guard_name || '';
        this.description = permission.description || '';
        this.modules(permission.modules || []);
        this.admins(permission.admins || []);
        this.role(permission.role || {});
    }

    /**
     * Permission hasOne WorkerRole relation
     *
     * @param {Object} role
     * @return {*}
     */
    role(role) {
        return this.hasOne(Role, role);
    }

    /**
     * Permission hasMany modules relation
     *
     * @param {Array} modules
     * @return {*}
     */
    modules(modules) {
        return this.hasMany(Module, modules);
    }

    /**
     * Permission hasMany Admins
     *
     * @param {Array} admins
     * @return {*}
     */
    admins(admins) {
        return this.hasMany(FranchiseAdmin, admins);
    }

    hasRole() {
        return !!this.role;
    }
}
