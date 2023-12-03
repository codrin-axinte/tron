<?php

namespace App\Enums;

enum TransactionBlockchainStatus: int
{
    /**
     * The transaction has been broadcast to the network but has not yet been included in a block.
     * This is a temporary state until the transaction is either confirmed or dropped.
     */
    case Pending = 0;

    /**
     * The transaction has been successfully executed and validated by the network.
     * The intended action (like transfer of TRX or a TRC20 token, contract execution, etc.) has been completed.
     */
    case Success = 1;
    /**
     * The transaction has failed to execute.
     * This can happen for various reasons, such as insufficient balance, invalid transaction format, or an issue with the smart contract execution.
     */
    case Failed = 2;
    /**
     * If a transaction is malformed, has incorrect signatures, or fails basic network checks, it is deemed invalid and not processed further.
     */
    case Invalid = 3;

    /**
     * Out of Energy or Bandwidth
     * Tron transactions require energy and bandwidth. If a transaction runs out of either, it may fail.
     * This is akin to running out of gas in Ethereum.
     */
    case OOEB = 4;
    /**
     * This happens when a smart contract transaction is reverted due to an exception or a specific revert operation in the contract.
     * In such cases, the TRX used for energy is still cons
     */
    case Reverted = 5;

}
