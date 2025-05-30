## Tech test submission

Hi there,

Thanks for sending over the tech test - here is my submission!

To quickly test soft deletion, you can pass a `with_deleted` boolean parameter to the index endpoint to include deleted tasks in the results.

I would have liked to spend more time on the unit / feature tests. The criteria asks for appropriate unit tests, however I thought the most important tests (covering the Api) should fall under "Feature". I realise I may have ended up with a slightly strange split between Unit and Feature, but I hope it shows a good start for test coverage. Also, I considered implementing a TaskRepository, however I decided - in this instance - it would be over engineering to separate the domain and data mapping layer.

Thanks again, I hope you have a good rest of your week!

## Task

Thank you for taking the time to complete this task. We appreciate your effort and look forward to reviewing your submission.

We would like you to create a basic Laravel API called **“Laravel Tech Task Demo API”**. The purpose of the API is to display, add, edit and delete tasks.

## Key criteria:
1. A task is formed of a name (min 3, max 100 characters) and a description (min 10, max 5000 characters).
2. Create an endpoint to view all tasks (no user restrictions).
3. A secured URL is required to edit or delete a task. Provide the appropriate endpoints to do this when a task is created.
4. No user accounts/ authentication is required.
5. Should be a RESTful API with a base of: `/api/tasks`.
6. Uses a NoSQL database (this is already setup).
7. Appropriate Unit Tests.
8. Uses Laravel Best Practices.

## Bonus Criteria:
1. All requests should be logged in a log file via middleware.
2. Implement Soft Deleting.

## Submission:
1. Respond to the email you received with a link to the fork of this repository with your solution in. Please include a `.env` file within your repository.
2. The three commands which will be used to run your solution will be:
    1. `composer install`
    2. `php artisan migrate`
    3. `php artisan serve`
