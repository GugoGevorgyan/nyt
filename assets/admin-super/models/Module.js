/** @format */

import Model from '../base/Model';
import Role from './Role';
import Franchise from './Franchise';
import Permission from './Permission';

export default class Module extends Model {
    /** @property roles {Array} **/
    /** @property permissions {Array} **/
    /** @property franchises {Array} **/

    static MODE_CREATE = 1;

    static MODE_UPDATE = 2;

    static MODE_DELETE = 3;

    showRoles = false;

    except = ['permissions', 'franchises', 'roles'];

    hidden = ['module_id', 'permission_ids', 'franchise_ids', 'showRoles'];

    rules = {
        name: 'required|string|max:255',
        alias: 'required|string|max:255',
        description: 'required|string',
        default: 'required|boolean',
    };

    /**
     * Module Constructor
     *
     * @param {Object} module
     */
    constructor(module = {}) {
        super('modules', 'admin/super');

        this.module_id = module.module_id || null;
        this.name = module.name || '';
        this.alias = module.alias || '';
        this.slug_name = module.slug_name || '';
        this.description = module.description || '';
        this.default = module.default || 0;
        this.roles(module.roles || []);
        this.franchises(module.franchises || []);
        this.permissions(module.permissions || []);
    }

    /**
     * Permission hasMany Roles relation
     *
     * @param {Array} roles
     * @return {*}
     */
    roles(roles) {
        return this.hasMany(Role, roles);
    }

    /**
     * Module hasMany Permissions relation Thought Roles
     *
     * @param {Array} permissions
     * @return {*}
     */
    permissions(permissions) {
        return this.hasMany(Permission, permissions);
    }

    /**
     * Module hasMany Franchises relation
     *
     * @param {Array} franchises
     * @return {*}
     */
    franchises(franchises) {
        return this.hasMany(Franchise, franchises);
    }

    /**
     * Check if module has related roles
     *
     * @return {number}
     */
    hasRoles() {
        return this.roles.length;
    }

    /**
     * Add role to Module
     *
     * @param {Role} role
     */
    addRole(role) {
        this.roles.push(role);
    }

    /**
     * Remove role from Module
     *
     * @param {Role} role
     * @param {Number} index
     */
    removeRole(role, index) {
        this.roles.splice(index, 1);
    }
}
