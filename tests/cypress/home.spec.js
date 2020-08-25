/// <reference types="Cypress" />
const server = Cypress.env('server');
// console.log('site: ', server);
// alert(server);

context('Home', () => {
    before(() => {
        cy.visit('https://wordpress.philgo.com');
    })
    describe('Login Test', () => {
        it('login test', () => {
            cy.get("[data-button='desktop-login']");
        })
    })
})
