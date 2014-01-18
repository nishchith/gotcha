<?php  
namespace Core;

class Db
{
	/**
	 * Insurance Table
	 */
	const DELTA_FETCH_INSURANCE		= "SELECT Insurance.* FROM Insurance WHERE lastUpdateDate >= :timestamp AND UserId = :userId AND Active = 1";
	// 	const DELTA_FETCH_INSURANCE		= "SELECT id, userId, vehicleId, renewDate, expiryDate, lastUpdateDate FROM Insurance WHERE lastUpdateDate >= :timestamp AND Id = :userId";

	/**
	 * Mileage Table
	 */
	const DELTA_FETCH_MILEAGE		= "SELECT Mileage.* FROM Mileage WHERE lastUpdateDate >= :timestamp AND UserId = :userId AND Active = 1";
	// const DELTA_FETCH_MILEAGE		= "SELECT id, userId, vehicleId, currentReading, fuelRefilled, refuelDate, mileage, lastUpdateDate FROM Mileage WHERE lastUpdateDate >= :timestamp AND Id = :userId";


	/**
	 * Model Table
	 */
	const DELTA_FETCH_MODEL				= "SELECT Model.* FROM Model WHERE lastUpdateDate >= :timestamp";
	// const DELTA_FETCH_MODEL		= "SELECT id, make, model, variant, type, reserve, viscometric, oemTieUp, recommeneded, packSize, lastUpdateDate FROM Model WHERE lastUpdateDate >= :timestamp";

	const DELTA_FETCH_MODEL_2WHEELER	= "SELECT Model.* FROM Model WHERE lastUpdateDate >= :timestamp AND Type = 2";
	const DELTA_FETCH_MODEL_4WHEELER	= "SELECT Model.* FROM Model WHERE lastUpdateDate >= :timestamp AND Type = 4";

	/**
	 * Note Table
	 */
	const DELTA_FETCH_NOTE			= "SELECT Note.* FROM Note WHERE lastUpdateDate >= :timestamp AND UserId = :userId AND Active = 1";
	// const DELTA_FETCH_NOTE		= "SELECT id, userId, vehicleId, message, date, lastUpdateDate FROM Note WHERE lastUpdateDate >= :timestamp AND Id = :userId";

	/**
	 * Puc Table
	 */
	// const DELTA_FETCH_PUC		= "SELECT Puc.* FROM Puc WHERE lastUpdateDate >= :timestamp AND UserId = :userId AND Active = 1";
	const DELTA_FETCH_PUC		= "SELECT Puc.* FROM Puc WHERE lastUpdateDate >= :timestamp AND UserId = :userId AND Active = 1";

	/**
	 * Service Table
	 */
	const DELTA_FETCH_SERVICE		= "SELECT Service.* FROM Service WHERE lastUpdateDate >= :timestamp AND UserId = :userId AND Active = 1";
	// const DELTA_FETCH_SERVICE		= "SELECT id, userId, vehicleId, currentReading, renewDate, lastUpdateDate FROM Service WHERE lastUpdateDate >= :timestamp AND Id = :userId";

	/**
	 * User Table
	 */
	const DELTA_FETCH_USER			= "SELECT User.* FROM User WHERE lastUpdateDate >= :timestamp AND Id = :userId";
	// const DELTA_FETCH_USER		= "SELECT id, fName, lName, dob, anniversary, city, state, relStatus, email, password, phone, lastUpdateDate FROM User WHERE lastUpdateDate >= :timestamp AND Id = :userId";

	const UPDATE_USER				= "UPDATE User SET FName = :fName , LName = :lName , DOB = :dob , Anniversary = :anniversary , City = :city , State = :state , RelStatus = :relStatus , Phone = :phone , LastUpdateDate = :lastUpdateDate WHERE Id = :userId";
	const UPDATE_USER_BY_EMAIL		= "UPDATE User SET FName = :fName , LName = :lName , DOB = :dob , Anniversary = :anniversary , City = :city , State = :state , RelStatus = :relStatus , Phone = :phone , LastUpdateDate = :lastUpdateDate WHERE Email = :email";
	const UPDATE_USER_PASSWORD		= "UPDATE User SET Password = :newPassword WHERE Email = :email";
       
	/**
	 * Vehicle Table
	 */
	const DELTA_FETCH_VEHICLE		= "SELECT V.Id, V.UserId, M.Model, M.Make, M.Variant, M.Type, V.RegNumber, V.CurrentReading, V.LastUpdateDate FROM  Vehicle V RIGHT OUTER JOIN Model M on V.ModelId = M.Id WHERE  V.LastUpdateDate >= :timestamp AND V.UserId = :userId AND V.Active = 1";
	// const DELTA_FETCH_VEHICLE		= "SELECT id, userId, modelId, regNumber, currentReading, lastUpdateDate FROM Vehicle WHERE lastUpdateDate >= :timestamp AND Id = :userId";
}