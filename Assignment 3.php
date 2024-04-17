<?php

// Sebatian Bruce – 200561191
// Black Jack Game

// Define the deck of cards
$deck = array(
    '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10,
    'J' => 10, 'Q' => 10, 'K' => 10, 'A' => 11
);

// Function to calculate the value of a hand
function calculateHandValue($hand) {

    $total = 0; // Initialize the total value of the hand
    $aces = 0; // Initialize the count of aces in the hand

    // Loop through each card in the hand
    foreach ($hand as $card) {
        $total += $card; // Add the value of the card to the total
        if ($card === 11) {
            $aces++; // If the card is an Ace, increment the count of aces
        }
    }
    
    // Adjust the total if there are aces and the total value exceeds 21
        while ($total > 21 && $aces > 0) {
        $total -= 10; // Reduce the total by 10 (since Ace can be 1 or 11)
        $aces--; // Decrement the count of aces
    }

    return $total; // Return the adjusted total value of the hand
}

// Variables
$money = 100; // Starting amount of money
$bet = 0; // Amount of money bet
$quit = false; // Boolean to quit the game

// Function to display choices for quitting or continuing
function displayChoices() {
    echo "Choose one of the following:\n";
    echo "1. Continue playing\n";
    echo "2. Quit\n";
}

// Main game loop
while (!$quit) {

    // Display current money and ask for bet
    echo "Money left: $" . $money . "\n";
    echo "Enter your bet: ";
    $bet = (int) readline(); // Read the bet amount from user input

    // Validate the bet amount
    if ($bet <= 0 || $bet > $money) {
        echo "Invalid bet amount. Please try again.\n";
        continue; // Restart the loop if the bet is invalid
    }

    // Deal initial cards
    $playerHand = [];
    $dealerHand = [];

    // Randomly pick two cards for the player and dealer
    $playerHand[] = $deck[array_rand($deck)];
    $dealerHand[] = $deck[array_rand($deck)];
    $playerHand[] = $deck[array_rand($deck)];
    $dealerHand[] = $deck[array_rand($deck)];

    // Display the player's cards and the dealer's visible card
    echo "Your cards: " . implode(', ', $playerHand) . " (Total: " . calculateHandValue($playerHand) . 
    ")\n";
    echo "Dealer's card: " . $dealerHand[0] . "\n";

    // Player's turn
    while (calculateHandValue($playerHand) <= 21) {

        echo "Choose one of the following:\n";
        echo "1. Hit\n";
        echo "2. Stand\n";

        $choice = (int) readline(); // Read the player's choice

        if ($choice === 1) {
            $playerHand[] = $deck[array_rand($deck)]; // Add a card to the player's hand
            echo "Your cards: " . implode(', ', $playerHand) . " (Total: " . 
            calculateHandValue($playerHand) . ")\n";

            if (calculateHandValue($playerHand) > 21) {
                echo "Busted! You lose.\n";
                $money -= $bet; // Deduct the bet amount from money
                break; // Exit the player's turn loop
            }

        } elseif ($choice === 2) {
            break; // Exit the player's turn loop

        } else {
            echo "Invalid choice. Please try again.\n";
        }
    }

    // Dealer's turn
    while (calculateHandValue($dealerHand) <= 17) {
        $dealerHand[] = $deck[array_rand($deck)]; // Dealer hits until total is at least 17
    }

    // Display the dealer's final hand
    echo "Dealer's cards: " . implode(', ', $dealerHand) . " (Total: " . 
    calculateHandValue($dealerHand) . ")\n";

    // Determine the winner and adjust money accordingly
    $playerValue = calculateHandValue($playerHand);
    $dealerValue = calculateHandValue($dealerHand);

    if ($playerValue <= 21 && ($dealerValue > 21 || $playerValue > $dealerValue)) {
        echo "Congratulations! You win.\n";
        $money += $bet; // Add the bet amount to money

    } elseif ($dealerValue <= 21 && ($playerValue > 21 || $dealerValue > $playerValue)) {
        echo "Dealer wins. You lose.\n";
        $money -= $bet; // Deduct the bet amount from money

    } elseif ($playerValue === $dealerValue) {
        echo "It's a tie.\n";
    }
    
    // Check if the player wants to continue playing
    displayChoices(); // Display choices for continuing or quitting
    $choice = (int) readline(); // Read the player's choice
    if ($choice !== 1) {
    $quit = true; // Set quit to true if the player chooses to quit
    }
}

echo "Game over. Final amount of money: $" . $money . "\n"; // Display final money amount
?>
