<?php

namespace App\Services;

use App\Traits\DateTrait;

class RsiaBtoService
{
	use DateTrait;

	protected $month;
	protected $year;
	protected $specialist;

	public function __construct(int $month = null, int $year = null)
	{
		$this->month = $month;
		$this->year = $year;
	}

	function setSpecialist($specialist)
	{
		$this->specialist = $specialist;
	}

	/**
	 * Jumlah pasien rawat inap keluar (hidup + meninggal)
	 */
	function getCountPasien()
	{
		return KamarInapService::getJumlahPasien(
			$this->specialist,
			$this->month,
			$this->year
		);
	}

	/**
	 * Jumlah tempat tidur
	 */
	function getCountTempatTidur()
	{
		return RsiaLogJumlahKamarService::getJumlahKamar(
			$this->specialist,
			$this->month,
			$this->year
		);
	}

	function get(string $specialist, int $year = null)
	{
		$this->setSpecialist($specialist);
		$this->setYear($year ?? date('Y'));

		$data = [];

		for ($i = 1; $i <= 12; $i++) {
			$this->setMonth($i);

			$jumlahPasien = $this->getCountPasien();
			$jumlahKamar = $this->getCountTempatTidur();

			$bto = $jumlahKamar
				? $jumlahPasien / $jumlahKamar
				: 0;

			$data[] = [
				'month' => $this->getMonthName($i),
				'year' => $this->year,
				'jumlahPasien' => $jumlahPasien,
				'jumlahKamar' => $jumlahKamar,
				'bto' => number_format($bto, 2),
			];
		}

		return $data;
	}
}