# A RestAPI back-end service for a Clothing website 

## Table of contents:
- [ Responsibilities](#head1)
- [API Documentation](#postman)
- [ To-do](#head2)
- [ Database Schema](#head3)
- [ How i Dockerize this project and local database](#head4)
- [ How i crawl data and store it in MySQL using Python and Javascript](#head6)

<a id="head1"></a>
## Responsibilities:
- Led a team of 3 members (2 frontend developers and myself as backend developer) to ensure cohesive project development and integration.
- Implemented a comprehensive set of features for both users and administrators in an e-commerce website using MVC architecture.
- Designed and optimized a detailed database structure for efficient data management.
- Integrated VNPay payment gateways for website functionality.
- Utilized Redis to temporarily reserve user orders during the payment process.
- Implemented JWT authentication for secure API access, and \textbf{role-based authorization middleware} for controlled resource access.
- Integrated Cloudinary for users to upload and retrieve images seamlessly from the cloud.
- Developed data crawling scripts using Python and JavaScript to collect data from a website and store it to the database.

<a id="postman"></a>
## API Documentation:
[<img src="https://run.pstmn.io/button.svg" alt="Run In Postman" style="width: 128px; height: 32px;">](https://app.getpostman.com/run-collection/29780789-24926342-d6a6-4b97-95cf-1676ab7b06ce?action=collection%2Ffork&source=rip_markdown&collection-url=entityId%3D29780789-24926342-d6a6-4b97-95cf-1676ab7b06ce%26entityType%3Dcollection%26workspaceId%3De0887007-fb8c-4887-9406-723e532eecd0)

<a id="head2"></a>
## To-do:
- [ ] Idempotent
- [ ] Database pooling
- [ ] Debounce and throttle
- [ ] Cloud
- [x] Exception, error, auth handler,...

<a id="head3"></a>
## Database Schema

![coolmate_diagram.png](coolmate_diagram.png)

<a id="head4"></a>
## [How i Dockerize this project and local database](https://toilacube.hashnode.dev/i-should-have-learned-docker-earlier)

<a id="head6"></a>
## [How i crawl data and store it in MySQL using Python and Javascript](https://github.com/toilacube/coolmate-data)
