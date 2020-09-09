/**
 * @file init.js
 */

if ( ! isLocalhost ) initServiceWorker();


$profile_photo
    .html(getUploadedFileHtml({
        ID: login('photo_ID'),
        thumbnail_url: myProfilePhotoUrl()
    }));



function AppViewModel() {
    const self = this;
    self.userProfilePhotoSrc = ko.observable(myProfilePhotoUrl());
    self.removeProfilePhoto = function() {
        self.userProfilePhotoSrc(null);
    }
}
$app = new AppViewModel();
ko.applyBindings($app);




// <ul data-bind="foreach: items">
//     <li>
//         Name: <i data-bind="text: name"></i>
//         Price: <i data-bind="text: price"></i>
//         <a href="#" data-bind="click: $parent.remove">Remove</a>
//     </li>
// </ul>
//
// <button type="button" data-bind="click: add">Add</button>
// <button type="button" data-bind="click: removeLast">Remove</button>


// function AppViewModel() {
//     const self = this;
//     this.data =  [
//         {name: 'Tomatoes', price: 324},
//         {name: 'Potatoes', price: 348},
//     ];
//     this.userProfilePhotoSrc = ko.observable(myProfilePhotoUrl());
//     // Must be observableArray or it is going work as a single variable.
//     this.items = ko.observableArray(this.data);
//     this.add = function() {
//         this.data.push({name: 'Banana', price: 401});
//         this.items(this.data);
//     }
//     this.remove = function() {
//         console.log(this);
//         // self refers root this.
//         // this refers the value of the data for that `foreach`
//         self.items.remove(this);
//     }
//     this.removeLast = function() {
//         this.items.pop();
//     }
// }
//
// $app = new AppViewModel();
// ko.applyBindings($app);
