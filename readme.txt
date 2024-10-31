=== Pixelavo - Facebook Pixel Conversion API / Server Side Tracking ===
Contributors: hasthemes, zenaulislam, tarekht, aslamhasib, yeasinrony
Tags: Facebook Pixel, Conversion API, Server Side Tracking, Analytics, WooCommerce, Facebook Pixel Events, Conversion Tracking,  Facebook Retargeting, Facebook Pixel for WooCommerce, Targeted Advertising, Facebook Analytics
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Pixelavo is a WordPress plugin that allows businesses to use Facebook's Conversions API and server-side tracking on their websites.

== Description ==
Pixelavo is a plugin that facilitates the quick and easy connection of your Facebook pixel to your online store. By utilizing the Facebook Pixel, you can gather vital information about your store's visitors, which can be leveraged to craft personalized Facebook advertisements. Consequently, this can generate additional traffic and sales for your online store. For individuals seeking to enhance their Facebook marketing campaigns with simplicity, Pixelavo serves as a valuable resource.

The plugin comes with a range of features to help you improve your marketing campaigns, including advanced tracking capabilities, custom audience creation, and retargeting tools. You can use the insights gathered by Pixelavo to create targeted and effective Facebook ads, which will increase the chances of converting potential customers into actual buyers.

Pixelavo is designed to be user-friendly, making it easy for anyone to install and use. Whether you're a beginner or an experienced marketer, Pixelavo provides you with the tools you need to improve your Facebook advertising campaigns and grow your business.

[Purchase Pro](https://hasthemes.com/plugins/facebook-pixel-wordpress-plugin/) | [Documentation](https://hasthemes.com/docs/pixelavo/how-to-use/) | [Support](https://hasthemes.com/contact-us/)

### Key features:

1. **Seamless integration:** Pixelavo seamlessly integrates with WordPress, allowing businesses to quickly and easily implement the Conversions API and server-side tracking on their website.
2. **Event tracking:** With Pixelavo, businesses can track a variety of events on their website, including purchases, leads, pageviews, and more. This data can be used to optimize Facebook advertising campaigns and improve targeting.
3. **Custom event tracking:** In addition to standard events, Pixelavo allows businesses to track custom events, such as button clicks, form submissions, or any other actions that are valuable to their business.
4. **Automatic event matching:** Pixelavo automatically matches events on the website to the corresponding events in Facebook Ads Manager, making it easy to track conversions and optimize campaigns.
5. **Real-time event tracking:** Pixelavo provides real-time event tracking, allowing businesses to quickly see how users are interacting with their website and make data-driven decisions about their advertising campaigns.
6. **Exclude Roles:** Exclude members of your team to ensure your Pixel data remains accurate. You can exclude specific WordPress user levels, such as Admins, Editors, and Contributors.
7. **Product Feed:** Create powerful Dynamic Product Ads and Custom Audiences for improved ad targeting and greater marketing success.
8. **Create Custom Events:** Interested in crafting personalized events to precisely monitor activities on your site, including page visits, button clicks, and link interactions? Crafting custom events is effortlessly achievable with Pixelavo.

### Available Events:

* **View Content**
* **View Category**
* **Search**
* **Add To Cart** [Pro]
* **Initiate Checkout** [Pro]
* **Purchase** [Pro]
* **Customize Product** (WooCommerce Only) [Pro]
* **PageScroll**
* **TimeOnPage**
* **InternalLinks** [Pro]
* **Video** (YouTube, Vimeo & SelfHosted) [Pro]

### Integrations:

* **Woocommerce**
* **Easy Digital Downloads (EDD)**

Overall, Pixelavo provides businesses with a comprehensive solution for implementing the Facebook Conversions API and server-side tracking on their WordPress website, with a range of features designed to improve data accuracy, privacy compliance, and campaign optimization.

### Pro Features: 

1. **Multiple pixel support:** Pixelavo allows businesses to add multiple Facebook Pixels to their website, making it easy to track data across different ad accounts or campaigns.
2. **WooCommerce integration:** Pixelavo integrates seamlessly with WooCommerce, allowing businesses to track purchases and other events related to their online store.
3. **Easy Digital Downloads (EDD):** Pixelavo effortlessly integrates with Easy Digital Downloads, providing businesses with the capability to track purchases and various events related to their online store..
4. **Exclude Bouncing Visitors:** Use our Time Delay setting to prevent bouncing visitors from triggering your Facebook Pixel event on product pages. By setting a time delay, the event will only fire after your visitor has spent a specified amount of time on your product page.
5. **Advanced Matching:** Enable Advanced Matching for all events. According to Facebook, advanced matching has assisted businesses in increasing attributed conversions by 10% and campaign reach by 20% through retargeting.
6. **Additional user information:** Include HTTP referrer, user language, post categories, and tags as event parameters, allowing you to build more precise custom audiences.
7. **Additional Purchase Information:** Include extra details such as coupon codes (if used) and shipping information as parameters with the Purchase event, enabling you to build more targeted and effective custom audiences.

### Facebook Conversions API

Facebook Conversions API (formerly known as the Server-Side API) is a tool that allows businesses to send their website or app conversion data directly to Facebook's servers, bypassing the need for browser-based tracking methods like the Facebook Pixel.
Using the Conversions API, businesses can track actions such as purchases, sign-ups, and other valuable events that occur on their website or app. This data can then be used to optimize Facebook advertising campaigns, such as retargeting ads to users who have already taken specific actions on the business's website or app.

### Some benefits of using Facebook Conversions API include:

1. **Improved accuracy:** By bypassing the need for browser-based tracking, businesses can ensure that all conversion data is captured accurately, even if a user's browser settings or ad-blocking software prevent the Facebook Pixel from tracking their actions.
2. **Increased privacy:** Because the Conversions API sends data directly to Facebook's servers, businesses can reduce the amount of user data that is shared with third-party trackers, potentially improving user privacy and security.
3. **Greater control:** The Conversions API allows businesses to track and send custom data points to Facebook, giving them greater flexibility and control over how they track and optimize their advertising campaigns.
4. **Reduced data loss:** Because the Conversions API relies on server-to-server communication, rather than browser-based tracking, businesses can reduce the risk of data loss due to issues like browser crashes or network outages.

Overall, the Facebook Conversions API can provide businesses with a more accurate, flexible, and privacy-focused way to track and optimize their Facebook advertising campaigns.

### 👨‍💻 Need Help?
Is there any feature that you want to get in this plugin?  
Needs assistance to use this plugin?  
Feel free to [Contact us](https://hasthemes.com/contact-us/)

== Changelog ==

= Version: 1.1.9 - Date: 02-10-2024 =
* Fixed: Issue attempt to read property on null in helper-functions.php

= Version: 1.1.8 - Date: 29-08-2024 =
* Fixed: Translation issue in pixel-extra-events-data.php file.
* Updated: Language pixelavo.pot file.

= Version: 1.1.7 - Date: 07-08-2024 =
* Added: New TimeOnPage event.
* Fixed: Call to undefined function is_shop() issue.

= Version: 1.1.6 - Date: 14-07-2024 =
* Added: New PageScroll event.
* Fixed: Undefined property: WP_Post_Type::$taxonomy issue.

= Version: 1.1.5 - Date: 23-06-2024 =
* Added: New options for choosing the Pixel List and Target Pages.

= Version: 1.1.4 - Date: 11-06-2024 =
* Tweak: Display a notification when data is saved or updated.

= Version: 1.1.3 - Date: 26-05-2024 =
* Fixed: Call to undefined function is_shop() issue for non-Woocommerce users.

= Version: 1.1.2 - Date: 23-05-2024 =
* Fixed: Issue with custom event "Page Visit" is not triggering on the shop page.

= Version: 1.1.1 - Date: 02-04-2024 =
* Fixed: Escaping issues have been resolved in multiple areas.

= Version: 1.1.0 - Date: 14-03-2024 =
* Fixed: Conversion API access token textarea remain focused.
* Improved: Removed dependency on JS for Diagnostic data notice dismiss issue

= Version: 1.0.9 - Date: 04-02-2024 =
* Added: Easy Digital Downloads (EDD) Integration.
* Updated: Language translation file.

= Version: 1.0.8 - Date: 18-01-2024 =
* Fixed: Switcher remain closed even if it was enabled.

= Version: 1.0.7 - Date: 28-12-2023 =
* Updated: Language translation .pot file
* Tweak: Opt-in message to provide non-sensitive diagnostic data and usage information to improve the plugin

= Version: 1.0.6 - Date: 25-11-2023 =
* Added: Custom events option.
* Fixed: Undefined error issue for the ViewContent event.

= Version: 1.0.5 - Date: 07-08-2023 =
* Tweak: Option to select timing for triggering Purchase event (pro)

= Version: 1.0.4 - Date: 08-05-2023 =
* Fixed: Server Event issue with Pixel ID.
* Improved: Form style.

= Version: 1.0.3 - Date: 30-04-2023 =
* Added: Exclude Roles option.
* Added: Woocommerce Product Feed option.
* Added: Compatibility with the latest WordPress version.

= Version: 1.0.2 - Date: 22-03-2023 =
* Fixed: Pixel limit compatibility with pro version.

= Version: 1.0.1 - Date: 12-03-2023 =
* Added: New 3 WooCommerce Event (ViewContent, Search and ViewCategory)
* Added: Conversion API support to track customer action more accurately.

= Version: 1.0.0 - Date: 11-03-2023 =
* Initial Release

== Installation ==
This section describes how to install the Pixelavo plugin and get it working.

1. Go to the WordPress Dashboard "Add New Plugin" section.
2. Search For "Pixelavo".
3. Install, then Activate it.

= OR: =
1. Unzip (if it is zipped) and Upload `pixelavo` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==
1. Pixel Listing
3. Add New Pixel Popup
2. WooCommerce Events List
4. Custom Events List
5. Add New Custom Event
6. Advance Settings
7. Easy Digital Downloads Events List
8. Other Events List
