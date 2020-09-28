/// <reference types="Cypress" />

import * as faker from 'faker/locale/en';
import { fake } from 'faker/locale/en';


const server = Cypress.env('server');
// console.log('site: ', server);
// alert(server);

context('Integration Test', () => {
    before(() => {

        cy.visit('https://wordpress.philgo.com');
    })
    beforeEach(() => {

        Cypress.Cookies.preserveOnce('session_id', 'user_email');

    })
    describe('Home', () => {
        it('Open home', () => {
            cy.get("[data-page='home']");
        })
    })
    describe('Register', () => {
        it('Submit register', () => {
            cy.get("[data-button='register']").click();
            // cy.get("[data-page='user.register']");
            // cy.get("form.register [name='user_email']").type(faker.internet.email());
            // cy.get("form.register [name='user_pass']").type(faker.internet.password());
            // cy.get("form.register [name='first_name']").type(faker.name.findName());
            // cy.get("form.register [name='middle_name']").type('J');
            // cy.get("form.register [name='last_name']").type(faker.name.lastName());
            // cy.get("form.register [name='nickname']").type(faker.name.firstName());
            // cy.get("form.register [name='mobile']").type(faker.phone.phoneNumber());
            // cy.get("form.register [data-button='submit']").click();
        })
    })

    // describe('Profile', () => {
    //     it('Submit profile', () => {
    //         cy.wait(500);
    //         cy.get("[data-button='profile']").click();
    //         cy.get("[data-button='profile-update']").click();
    //         cy.get("form.register [name='mobile']").clear().type(faker.phone.phoneNumber());
    //         cy.get("form.register [data-button='submit']").click();
    //     })
    // })
    // describe('Logout', () => {
    //     it('Do logout', () => {
    //         cy.wait(500);
    //         cy.get("[data-button='logout']").click();
    //     })
    // })


})
