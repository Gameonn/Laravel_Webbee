<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, 
     * for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // create movies table
        Schema::create('movies', function(Blueprint $table)
		{
			$table->integer('movie_id');
			$table->integer('movie_title');
			$table->string('description');
			$table->string('genre');
			$table->timestamp('duration');

			$table->primary('movie_id');
		});

        // create cinemas table
        Schema::create('cinemas', function(Blueprint $table)
		{
			$table->integer('cinema_id');
			$table->string('city');

			$table->primary('cinema_id');
        });

        // create halls table
        Schema::create('halls', function(Blueprint $table)
		{
			$table->integer('hall_id');
			$table->integer('cinema_id');
			$table->string('name');
			$table->string('address');
			$table->integer('seat_count');

			$table->primary('hall_id');
            $table->foreign('cinema_id')->references('cinema_id')->on('cinemas');
        });

        // create users table
        Schema::table('users', function(Blueprint $table)
		{
			$table->string('mobile_number');
			$table->enum('user_role', ['user', 'admin', 'manager'])->default('user');

		});

        // create shows table
        Schema::create('shows', function(Blueprint $table)
		{
            $table->integer('show_id');
            $table->integer('movie_id');
			$table->integer('hall_id');
			$table->integer('price');
			$table->datetime('start_time');
			$table->datetime('end_time');

			$table->primary('show_id');
            $table->foreign('movie_id')->references('movie_id')->on('movies');
            $table->foreign('hall_id')->references('hall_id')->on('halls');
        });
        

        // create bookings table
        Schema::create('bookings', function(Blueprint $table)
		{
			$table->integer('booking_id');
			$table->integer('show_id');
			$table->integer('user_id');
			$table->integer('number_of_seats');
			$table->enum('status', ['Confirmed', 'Waiting', 'Reject', 'Cancelled']);
            $table->timestamps();

			$table->primary('booking_id');
            $table->foreign('show_id')->references('show_id')->on('shows');
        });

        // create hall_seats table
        Schema::create('hall_seats', function(Blueprint $table)
		{
			$table->integer('hall_seat_id');
			$table->integer('hall_id');
			$table->enum('seat_type', ['vip', 'super vip', 'normal'])->default('normal');
			$table->integer('seat_number');

			$table->primary('hall_seat_id');
            $table->foreign('hall_id')->references('hall_id')->on('halls');
        });

        // create seats table
        Schema::create('seats', function(Blueprint $table)
		{
			$table->integer('seat_id');
			$table->integer('hall_seat_id');
			$table->integer('show_id');
			$table->integer('booking_id');
			$table->integer('price');
			$table->enum('status', ['occupied', 'idle']);

			$table->primary('seat_id');
            $table->foreign('hall_seat_id')->references('hall_seat_id')->on('hall_seats');
            $table->foreign('show_id')->references('show_id')->on('shows');
            $table->foreign('booking_id')->references('booking_id')->on('bookings');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // drop the movies table
		Schema::dropIfExists('movies');
        
		// drop the shows table
		Schema::dropIfExists('shows');
        
		// drop the cinemas table
		Schema::dropIfExists('cinemas');
        
		// drop the bookings table
		Schema::dropIfExists('bookings');
        
		// drop the users table
		Schema::dropIfExists('users');
        
		// drop the halls table
		Schema::dropIfExists('halls');
        
		// drop the halls table
		Schema::dropIfExists('hall_seats');
        
		// drop the halls table
		Schema::dropIfExists('seats');
    
        // enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
