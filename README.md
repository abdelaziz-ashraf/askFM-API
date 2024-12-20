# AskFM API Clone

## Project Description
This project is an API-only clone of AskFM, allowing users to ask, answer and like questions.

---

## Features
- User registration and login with email verification using code verification.
- Ask questions to other users.
- Answer and delete your received questions.
- Like or unlike answered questions.
- Show all questions.
- Show answered questions for a specific user.

---

## Technologies
- **Framework**: Laravel 11
- **Authentication**: Sanctum
- **Database**: MySQL
- **Languages**: PHP 8.1+
- **Other**: RESTful API

### What I Learned
- Sanctum.
- Notification System.
- Events and Listeners.
- Task Scheduler.

---

## API Endpoints

### Authentication
- **POST** `/auth/register` - User registration.
- **POST** `/auth/login` - User login.
- **POST** `/auth/email/verify-code` - Verify email with a code.

### Questions
- **GET** `/questions/` - Get recommended questions.
- **POST** `/questions/{receiver}` - Ask a question.
- **DELETE** `/questions/{question}` - Delete a question.
- **PATCH** `/questions/{question}/toggle-like` - Like/unlike a question.

### Answers
- **GET** `/answers/{user}` - Get all answered questions by a user.
- **POST** `/answers/{question}` - Answer a question.
- **DELETE** `/answers/{question}` - Delete an answer.

---

## Database Structure

### Users Table
| Column         | Type        | Description                     |  
|----------------|-------------|---------------------------------|  
| `id`           | BigInt      | Primary key.                    |  
| `name`         | String      | User's name.                    |  
| `email`        | String      | Unique email.                   |  
| `password`     | String      | Encrypted password.             |  
| `image`        | String      | Profile image (optional).       |  
| `email_verified_at` | Timestamp | Email verification timestamp. |  

### Questions Table
| Column         | Type        | Description                     |  
|----------------|-------------|---------------------------------|  
| `id`           | BigInt      | Primary key.                    |  
| `sender`       | Foreign Key | Reference to `users.id`. Can be null. |  
| `receiver`     | Foreign Key | Reference to `users.id`. Cannot be null. |  
| `body`         | Text        | Question content.               |  
| `answer`       | Text        | Answer content (optional).      |  

### Likes Table
| Column         | Type        | Description                     |  
|----------------|-------------|---------------------------------|  
| `id`           | BigInt      | Primary key.                    |  
| `user_id`      | Foreign Key | Reference to `users.id`.        |  
| `question_id`  | Foreign Key | Reference to `questions.id`.    |  

---

## Setup Instructions
1. **Clone the repository**:
   ```bash  
   git clone <repository-url>  
   cd <repository-folder>  
   ```  

2. **Install dependencies**:
   ```bash  
   composer install  
   ```  

3. **Set up `.env` file**:
    - Copy `.env.example` to `.env`.
    - Configure database and Sanctum settings.

4. **Run migrations**:
   ```bash  
   php artisan migrate  
   ```  

5. **Start the server**:
   ```bash  
   php artisan serve  
   ```  

---

## License
This project is open-source and available under the [MIT License](LICENSE).

---  
