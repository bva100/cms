A new CMS platform. Focuses on solving the following problems which regularly occur with incumbent CMS soluctions:

1. Difficult for end user to use
2. Difficult to manipulate JS, CSS and HTML directly for advanced users
3. Constant plugin and CSS/JS collisions
4. Deployment issues including: ftp, inccurect urls, client's don't update, dev/prod development, lack of version control, cheap hosting environments lead to unreliable scripts, to get full functionality installation is tricky
5. Performance issues
6. Security issues
7. Poor mobile solutions lead to bad UX on mobile devices, slow load speeds
8. Multilingual sites are difficult to create and manage
9. Multi site setup is hard to do. Configs and resused files are very hard to migrate
10. Access control is confusing (drupal) or lacks robustness (wordpress, joomla)
11. Lack of quality inline WYSIWYG editor
12. Search functionality is weak at best
13. Managing and setting up meta data requires too many fields, confusing since we don't always need this data
14. Always outputs entire page which makes it difficult to pull simple json/xml data

Furthermore, includes features which current incumbents solve well
1. Custom content (post) types
2. Robust categories and tagging
3. Ability to generate both static and dynamic posts
4. Robust commenting systems
5. Robust content creation systems with WYSIWYG

Technology solutions
1. CMS as a service
2. NoSQL with embedded docs via node pattern
3. Mini JS/CSS repos for users
4. Twig for accessing dynamic vars and template extension
5. RESTful processing for CRUD operations
6. iFrame and public API w/ oauth2 for 3rd party devs
7. Http caching and ESI
